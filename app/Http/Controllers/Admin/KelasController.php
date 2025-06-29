<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class KelasController extends Controller
{
    private $apiBaseUrl;
    private $prodiApiUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/kelas';
        $this->prodiApiUrl = config('app.api_url') . '/api/program-studi';
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

            if ($request->filled('prodi_filter')) {
                $query['id_prodi'] = $request->prodi_filter;
            }

            if ($request->filled('tahun_filter')) {
                $query['tahun_angkatan'] = $request->tahun_filter;
            }

            if ($request->filled('page')) {
                $query['page'] = $request->page;
            }

            // Get Kelas data
            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            // Get Program Studi data for filter dropdown
            $prodiResponse = Http::withToken($token)->get($this->prodiApiUrl);
            $prodiList = [];

            if ($prodiResponse->successful()) {
                $prodiResult = $prodiResponse->json();
                $prodiList = $prodiResult['data'] ?? $prodiResult;
            }

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['data']) && is_array($result['data'])) {
                    $allKelas = $this->mapProdiNames($result['data'], $prodiList);
                    $kelas = $this->createManualPagination($allKelas, $request);
                }
                 else {
                    $allKelas = $result['data'] ?? $result;
                    $allKelas = $this->mapProdiNames($allKelas, $prodiList);
                    $kelas = $this->createManualPagination($allKelas, $request);
                }
                // dd($result);
            } else {
                Log::error('Kelas API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data kelas dari server.');
                $kelas = collect([]);
            }

            // Get unique tahun angkatan for filter
            $tahunAngkatanList = $this->getTahunAngkatanList($kelas);

        } catch (\Exception $e) {
            Log::error('KelasController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data kelas.');
            $kelas = collect([]);
            $tahunAngkatanList = [];
        }


        return view('admin.dashboard.kelas.index', compact('kelas', 'prodiList', 'tahunAngkatanList'));
    }

    public function create()
    {
        $token = Session::get('token');

        try {
            // Get program studi list for dropdown
            $prodiResponse = Http::withToken($token)->get($this->prodiApiUrl);
            $prodiList = [];

            if ($prodiResponse->successful()) {
                $prodiResult = $prodiResponse->json();
                $prodiList = $prodiResult['data'] ?? $prodiResult;
            }

            return view('admin.dashboard.kelas.form', compact('prodiList'));
        } catch (\Exception $e) {
            Log::error('Create Kelas Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memuat form.');
            return redirect()->route('admin.kelas');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_prodi' => 'required|integer',
            'tahun_angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'nama_kelas' => trim($request->nama_kelas),
                'id_prodi' => $request->id_prodi,
                'tahun_angkatan' => $request->tahun_angkatan
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Kelas berhasil ditambahkan.');
                return redirect()->route('admin.kelas');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan kelas.']);

        } catch (\Exception $e) {
            Log::error('Store Kelas Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan kelas.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            // Get kelas data
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            // Get program studi list for dropdown
            $prodiResponse = Http::withToken($token)->get($this->prodiApiUrl);
            $prodiList = [];

            if ($prodiResponse->successful()) {
                $prodiResult = $prodiResponse->json();
                $prodiList = $prodiResult['data'] ?? $prodiResult;
            }

            if ($response->successful()) {
                $result = $response->json();
                $kelas = $result['data'] ?? null;

                if ($kelas) {
                    // Map program studi name
                    $kelas = $this->mapProdiNames([$kelas], $prodiList)[0];
                    return view('admin.dashboard.kelas.form', compact('kelas', 'prodiList'));
                }
            }


            session()->flash('error', 'Kelas tidak ditemukan.');
            return redirect()->route('admin.kelas');

        } catch (\Exception $e) {
            Log::error('Edit Kelas Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data kelas.');
            return redirect()->route('admin.kelas');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'id_prodi' => 'required|integer',
            'tahun_angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'nama_kelas' => trim($request->nama_kelas),
                'id_prodi' => $request->id_prodi,
                'tahun_angkatan' => $request->tahun_angkatan
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Kelas berhasil diperbarui.');
                return redirect()->route('admin.kelas');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui kelas.']);

        } catch (\Exception $e) {
            Log::error('Update Kelas Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui kelas.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Kelas berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus kelas.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Kelas Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus kelas.');
        }

        return redirect()->route('admin.kelas');
    }

    private function mapProdiNames($kelasData, $prodiList)
    {
        if (!is_array($prodiList)) {
            return $kelasData;
        }

        // Create program studi lookup array
        $prodiLookup = [];
        foreach ($prodiList as $prodi) {
            $prodiLookup[$prodi['id']] = $prodi['nama_prodi'];
        }

        // Map program studi names to kelas data
        foreach ($kelasData as &$kelas) {
            if (isset($kelas['program_studi']) && isset($kelas['program_studi']['nama_prodi'])) {
                $kelas['nama_prodi'] = $kelas['program_studi']['nama_prodi'];
            } else {
                $kelas['nama_prodi'] = $prodiLookup[$kelas['id_prodi']] ?? 'Program Studi Tidak Ditemukan';
            }
        }

        return $kelasData;
    }

    private function getTahunAngkatanList($kelas)
    {
        $tahunList = [];

        if ($kelas instanceof LengthAwarePaginator) {
            foreach ($kelas->items() as $item) {
                if (isset($item['tahun_angkatan'])) {
                    $tahunList[] = $item['tahun_angkatan'];
                }
            }
        } else {
            foreach ($kelas as $item) {
                if (isset($item['tahun_angkatan'])) {
                    $tahunList[] = $item['tahun_angkatan'];
                }
            }
        }

        return array_unique($tahunList);
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

    private function createManualPagination($allKelas, $request)
    {
        $allKelas = is_array($allKelas) ? $allKelas : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allKelas = array_filter($allKelas, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_kelas']), $searchTerm) !== false ||
                       strpos(strtolower($item['nama_prodi'] ?? ''), $searchTerm) !== false;
            });
        }

        // Apply program studi filter
        if ($request->filled('prodi_filter')) {
            $prodiId = $request->prodi_filter;
            $allKelas = array_filter($allKelas, function ($item) use ($prodiId) {
                return $item['id_prodi'] == $prodiId;
            });
        }

        // Apply tahun angkatan filter
        if ($request->filled('tahun_filter')) {
            $tahun = $request->tahun_filter;
            $allKelas = array_filter($allKelas, function ($item) use ($tahun) {
                return $item['tahun_angkatan'] == $tahun;
            });
        }

        $total = count($allKelas);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allKelas, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}