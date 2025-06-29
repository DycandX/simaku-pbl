<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class TahunAkademikController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/tahun-akademik';
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
                    $tahunAkademik = $this->createPaginationObject($result, $request);
                } else {
                    $allTahunAkademik = $result['data'] ?? $result;
                    $tahunAkademik = $this->createManualPagination($allTahunAkademik, $request);
                }

            } else {
                Log::error('Tahun Akademik API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data tahun akademik dari server.');
                $tahunAkademik = collect([]);
            }
        } catch (\Exception $e) {
            Log::error('TahunAkademikController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data tahun akademik.');
            $tahunAkademik = collect([]);
        }

        return view('admin.dashboard.tahun-akademik.index', compact('tahunAkademik'));
    }

    public function create()
    {
        return view('admin.dashboard.tahun-akademik.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:255',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'tahun_akademik' => trim($request->tahun_akademik),
                'semester' => $request->semester,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Tahun akademik berhasil ditambahkan.');
                return redirect()->route('admin.tahun-akademik');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan tahun akademik.']);

        } catch (\Exception $e) {
            Log::error('Store Tahun Akademik Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan tahun akademik.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                $tahunAkademik = $result['data'] ?? null;

                if ($tahunAkademik) {
                    return view('admin.dashboard.tahun-akademik.form', compact('tahunAkademik'));
                }
            }

            session()->flash('error', 'Tahun akademik tidak ditemukan.');
            return redirect()->route('admin.tahun-akademik');

        } catch (\Exception $e) {
            Log::error('Edit Tahun Akademik Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data tahun akademik.');
            return redirect()->route('admin.tahun-akademik');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:255',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'tahun_akademik' => trim($request->tahun_akademik),
                'semester' => $request->semester,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Tahun akademik berhasil diperbarui.');
                return redirect()->route('admin.tahun-akademik');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui tahun akademik.']);

        } catch (\Exception $e) {
            Log::error('Update Tahun Akademik Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui tahun akademik.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Tahun akademik berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus tahun akademik.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Tahun Akademik Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus tahun akademik.');
        }

        return redirect()->route('admin.tahun-akademik');
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

    private function createManualPagination($allTahunAkademik, $request)
    {
        $allTahunAkademik = is_array($allTahunAkademik) ? $allTahunAkademik : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allTahunAkademik = array_filter($allTahunAkademik, function ($item) use ($searchTerm) {
                $tahunMatch = strpos(strtolower($item['tahun_akademik']), $searchTerm) !== false;
                $semesterMatch = strpos(strtolower($item['semester']), $searchTerm) !== false;
                $statusMatch = strpos(strtolower($item['status']), $searchTerm) !== false;
                return $tahunMatch || $semesterMatch || $statusMatch;
            });
        }

        $total = count($allTahunAkademik);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allTahunAkademik, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}