<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class TingkatController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/tingkat';
    }

    public function index(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $query = [];

            if ($request->filled('search')) {
                $query['search'] = $request->search;
            }

            if ($request->filled('page')) {
                $query['page'] = $request->page;
            }

            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['data']) && is_array($result['data'])) {
                    $tingkat = $this->createPaginationObject($result, $request);
                } else {
                    $allTingkat = $result['data'] ?? $result;
                    $tingkat = $this->createManualPagination($allTingkat, $request);
                }

            } else {
                Log::error('Tingkat API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data tingkat dari server.');
                $tingkat = collect([]);
            }
        } catch (\Exception $e) {
            Log::error('TingkatController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data tingkat.');
            $tingkat = collect([]);
        }

        return view('admin.dashboard.tingkat.index', compact('tingkat'));
    }

    public function create()
    {
        return view('admin.dashboard.tingkat.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'nama_tingkat' => trim($request->nama_tingkat),
                'deskripsi' => $request->deskripsi ? trim($request->deskripsi) : null,
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Tingkat berhasil ditambahkan.');
                return redirect()->route('admin.tingkat');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan tingkat.']);

        } catch (\Exception $e) {
            Log::error('Store Tingkat Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan tingkat.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                $tingkat = $result['data'] ?? null;

                if ($tingkat) {
                    return view('admin.dashboard.tingkat.form', compact('tingkat'));
                }
            }

            session()->flash('error', 'Tingkat tidak ditemukan.');
            return redirect()->route('admin.tingkat');

        } catch (\Exception $e) {
            Log::error('Edit Tingkat Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data tingkat.');
            return redirect()->route('admin.tingkat');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'nama_tingkat' => trim($request->nama_tingkat),
                'deskripsi' => $request->deskripsi ? trim($request->deskripsi) : null,
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Tingkat berhasil diperbarui.');
                return redirect()->route('admin.tingkat');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui tingkat.']);

        } catch (\Exception $e) {
            Log::error('Update Tingkat Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui tingkat.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Tingkat berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus tingkat.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Tingkat Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus tingkat.');
        }

        return redirect()->route('admin.tingkat');
    }

    private function createPaginationObject($data, $request)
    {
        $currentPage = $data['current_page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;
        $total = $data['total'] ?? count($data['data']);
        $items = collect($data['data']);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }

    private function createManualPagination($allTingkat, $request)
    {
        $allTingkat = is_array($allTingkat) ? $allTingkat : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allTingkat = array_filter($allTingkat, function ($item) use ($searchTerm) {
                $namaMatch = strpos(strtolower($item['nama_tingkat']), $searchTerm) !== false;
                $deskripsiMatch = isset($item['deskripsi']) && strpos(strtolower($item['deskripsi']), $searchTerm) !== false;
                return $namaMatch || $deskripsiMatch;
            });
        }

        $total = count($allTingkat);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allTingkat, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}