<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class FakultasController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/fakultas';
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
                    $fakultas = $this->createPaginationObject($result, $request);
                } else {
                    $allFakultas = $result['data'] ?? $result;
                    $fakultas = $this->createManualPagination($allFakultas, $request);
                }

            } else {
                Log::error('Fakultas API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data fakultas dari server.');
                $fakultas = collect([]);
            }
        } catch (\Exception $e) {
            Log::error('FakultasController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data fakultas.');
            $fakultas = collect([]);
        }

        return view('admin.dashboard.fakultas.index', compact('fakultas'));
    }

    public function create()
    {
        return view('admin.dashboard.fakultas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = ['nama_fakultas' => trim($request->nama_fakultas)];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Fakultas berhasil ditambahkan.');
                return redirect()->route('admin.fakultas');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan fakultas.']);

        } catch (\Exception $e) {
            Log::error('Store Fakultas Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan fakultas.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                $fakultas = $result['data'] ?? null;

                if ($fakultas) {
                    return view('admin.dashboard.fakultas.form', compact('fakultas'));
                }
            }

            session()->flash('error', 'Fakultas tidak ditemukan.');
            return redirect()->route('admin.fakultas');

        } catch (\Exception $e) {
            Log::error('Edit Fakultas Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data fakultas.');
            return redirect()->route('admin.fakultas');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255',
        ]);

        $token = Session::get('token');

        try {
            $data = ['nama_fakultas' => trim($request->nama_fakultas)];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Fakultas berhasil diperbarui.');
                return redirect()->route('admin.fakultas');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui fakultas.']);

        } catch (\Exception $e) {
            Log::error('Update Fakultas Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui fakultas.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Fakultas berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus fakultas.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Fakultas Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus fakultas.');
        }

        return redirect()->route('admin.fakultas');
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

    private function createManualPagination($allFakultas, $request)
    {
        $allFakultas = is_array($allFakultas) ? $allFakultas : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allFakultas = array_filter($allFakultas, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_fakultas']), $searchTerm) !== false;
            });
        }

        $total = count($allFakultas);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allFakultas, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}
