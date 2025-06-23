<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class ProgramStudiController extends Controller
{
    private $apiBaseUrl;
    private $fakultasApiUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/program-studi';
        $this->fakultasApiUrl = config('app.api_url') . '/api/fakultas';
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

            if ($request->filled('fakultas_filter')) {
                $query['id_fakultas'] = $request->fakultas_filter;
            }

            if ($request->filled('page')) {
                $query['page'] = $request->page;
            }

            // Get Program Studi data
            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            // Get Fakultas data for filter dropdown
            $fakultasResponse = Http::withToken($token)->get($this->fakultasApiUrl);
            $fakultasList = [];

            if ($fakultasResponse->successful()) {
                $fakultasResult = $fakultasResponse->json();
                $fakultasList = $fakultasResult['data'] ?? $fakultasResult;
            }

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['data']) && is_array($result['data'])) {
                    // Map fakultas names to program studi data
                    $programStudiData = $this->mapFakultasNames($result['data'], $fakultasList);
                    $result['data'] = $programStudiData;
                    $programStudi = $this->createPaginationObject($result, $request);
                } else {
                    $allProgramStudi = $result['data'] ?? $result;
                    $allProgramStudi = $this->mapFakultasNames($allProgramStudi, $fakultasList);
                    $programStudi = $this->createManualPagination($allProgramStudi, $request);
                }

            } else {
                Log::error('Program Studi API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data program studi dari server.');
                $programStudi = collect([]);
            }
        } catch (\Exception $e) {
            Log::error('ProgramStudiController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data program studi.');
            $programStudi = collect([]);
        }

        return view('admin.dashboard.program-studi.index', compact('programStudi', 'fakultasList'));
    }

    public function create()
    {
        $token = Session::get('token');

        try {
            // Get fakultas list for dropdown
            $fakultasResponse = Http::withToken($token)->get($this->fakultasApiUrl);
            $fakultasList = [];

            if ($fakultasResponse->successful()) {
                $fakultasResult = $fakultasResponse->json();
                $fakultasList = $fakultasResult['data'] ?? $fakultasResult;
            }

            return view('admin.dashboard.program-studi.form', compact('fakultasList'));
        } catch (\Exception $e) {
            Log::error('Create Program Studi Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memuat form.');
            return redirect()->route('admin.program-studi');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'id_fakultas' => 'required|integer',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'nama_prodi' => trim($request->nama_prodi),
                'id_fakultas' => $request->id_fakultas
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Program Studi berhasil ditambahkan.');
                return redirect()->route('admin.program-studi');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan program studi.']);

        } catch (\Exception $e) {
            Log::error('Store Program Studi Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan program studi.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            // Get program studi data
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            // Get fakultas list for dropdown
            $fakultasResponse = Http::withToken($token)->get($this->fakultasApiUrl);
            $fakultasList = [];

            if ($fakultasResponse->successful()) {
                $fakultasResult = $fakultasResponse->json();
                $fakultasList = $fakultasResult['data'] ?? $fakultasResult;
            }

            if ($response->successful()) {
                $result = $response->json();
                $programStudi = $result['data'] ?? null;

                if ($programStudi) {
                    return view('admin.dashboard.program-studi.form', compact('programStudi', 'fakultasList'));
                }
            }

            session()->flash('error', 'Program Studi tidak ditemukan.');
            return redirect()->route('admin.program-studi');

        } catch (\Exception $e) {
            Log::error('Edit Program Studi Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data program studi.');
            return redirect()->route('admin.program-studi');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'id_fakultas' => 'required|integer',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'nama_prodi' => trim($request->nama_prodi),
                'id_fakultas' => $request->id_fakultas
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Program Studi berhasil diperbarui.');
                return redirect()->route('admin.program-studi');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui program studi.']);

        } catch (\Exception $e) {
            Log::error('Update Program Studi Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui program studi.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Program Studi berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus program studi.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Program Studi Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus program studi.');
        }

        return redirect()->route('admin.program-studi');
    }

    private function mapFakultasNames($programStudiData, $fakultasList)
    {
        if (!is_array($fakultasList)) {
            return $programStudiData;
        }

        // Create fakultas lookup array
        $fakultasLookup = [];
        foreach ($fakultasList as $fakultas) {
            $fakultasLookup[$fakultas['id']] = $fakultas['nama_fakultas'];
        }

        // Map fakultas names to program studi data
        foreach ($programStudiData as &$prodi) {
            $prodi['nama_fakultas'] = $fakultasLookup[$prodi['id_fakultas']] ?? 'Fakultas Tidak Ditemukan';
        }

        return $programStudiData;
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

    private function createManualPagination($allProgramStudi, $request)
    {
        $allProgramStudi = is_array($allProgramStudi) ? $allProgramStudi : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allProgramStudi = array_filter($allProgramStudi, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_prodi']), $searchTerm) !== false ||
                       strpos(strtolower($item['nama_fakultas'] ?? ''), $searchTerm) !== false;
            });
        }

        // Apply fakultas filter
        if ($request->filled('fakultas_filter')) {
            $fakultasId = $request->fakultas_filter;
            $allProgramStudi = array_filter($allProgramStudi, function ($item) use ($fakultasId) {
                return $item['id_fakultas'] == $fakultasId;
            });
        }

        $total = count($allProgramStudi);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allProgramStudi, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}