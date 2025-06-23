<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class MahasiswaController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/mahasiswa';
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

            // Get Mahasiswa data
            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['data']) && is_array($result['data'])) {
                    $mahasiswa = $this->createPaginationObject($result, $request);
                } else {
                    $allMahasiswa = $result['data'] ?? $result;
                    $mahasiswa = $this->createManualPagination($allMahasiswa, $request);
                }

            } else {
                Log::error('Mahasiswa API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data mahasiswa dari server.');
                $mahasiswa = collect([]);
            }

        } catch (\Exception $e) {
            Log::error('MahasiswaController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data mahasiswa.');
            $mahasiswa = collect([]);
        }

        return view('admin.dashboard.mahasiswa.index', compact('mahasiswa'));
    }

    public function create()
    {
        return view('admin.dashboard.mahasiswa.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'nim' => trim($request->nim),
                'nama_lengkap' => trim($request->nama_lengkap),
                'alamat' => trim($request->alamat),
                'no_telepon' => trim($request->no_telepon)
            ];

            // Handle file upload if exists
            if ($request->hasFile('foto')) {
                $response = Http::withToken($token)
                    ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                    ->post($this->apiBaseUrl, $data);
            } else {
                $response = Http::withToken($token)->post($this->apiBaseUrl, $data);
            }

            if ($response->successful()) {
                session()->flash('success', 'Mahasiswa berhasil ditambahkan.');
                return redirect()->route('admin.mahasiswa');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan mahasiswa.']);

        } catch (\Exception $e) {
            Log::error('Store Mahasiswa Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan mahasiswa.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            // Get mahasiswa data
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                $mahasiswa = $result['data'] ?? null;

                if ($mahasiswa) {
                    return view('admin.dashboard.mahasiswa.form', compact('mahasiswa'));
                }
            }

            session()->flash('error', 'Mahasiswa tidak ditemukan.');
            return redirect()->route('admin.mahasiswa');

        } catch (\Exception $e) {
            Log::error('Edit Mahasiswa Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data mahasiswa.');
            return redirect()->route('admin.mahasiswa');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'nim' => trim($request->nim),
                'nama_lengkap' => trim($request->nama_lengkap),
                'alamat' => trim($request->alamat),
                'no_telepon' => trim($request->no_telepon)
            ];

            // Handle file upload if exists
            if ($request->hasFile('foto')) {
                $response = Http::withToken($token)
                    ->attach('foto', file_get_contents($request->file('foto')), $request->file('foto')->getClientOriginalName())
                    ->put($this->apiBaseUrl . '/' . $id, $data);
            } else {
                $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);
            }

            if ($response->successful()) {
                session()->flash('success', 'Mahasiswa berhasil diperbarui.');
                return redirect()->route('admin.mahasiswa');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui mahasiswa.']);

        } catch (\Exception $e) {
            Log::error('Update Mahasiswa Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui mahasiswa.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Mahasiswa berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus mahasiswa.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Mahasiswa Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus mahasiswa.');
        }

        return redirect()->route('admin.mahasiswa');
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

    private function createManualPagination($allMahasiswa, $request)
    {
        $allMahasiswa = is_array($allMahasiswa) ? $allMahasiswa : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allMahasiswa = array_filter($allMahasiswa, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_lengkap']), $searchTerm) !== false ||
                       strpos(strtolower($item['nim']), $searchTerm) !== false ||
                       strpos(strtolower($item['alamat'] ?? ''), $searchTerm) !== false ||
                       strpos(strtolower($item['no_telepon'] ?? ''), $searchTerm) !== false;
            });
        }

        $total = count($allMahasiswa);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allMahasiswa, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}