<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/staff';
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

            if ($request->filled('jabatan_filter')) {
                $query['jabatan'] = $request->jabatan_filter;
            }

            if ($request->filled('unit_filter')) {
                $query['unit_kerja'] = $request->unit_filter;
            }

            if ($request->filled('page')) {
                $query['page'] = $request->page;
            }

            // Get Staff data
            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['data']) && is_array($result['data'])) {
                    $staff = $this->createPaginationObject($result, $request);
                } else {
                    $allStaff = $result['data'] ?? $result;
                    $staff = $this->createManualPagination($allStaff, $request);
                }

                // Get unique jabatan and unit kerja for filters
                $jabatanList = $this->getJabatanList($staff);
                $unitList = $this->getUnitKerjaList($staff);

            } else {
                Log::error('Staff API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data staff dari server.');
                $staff = collect([]);
                $jabatanList = [];
                $unitList = [];
            }

        } catch (\Exception $e) {
            Log::error('StaffController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data staff.');
            $staff = collect([]);
            $jabatanList = [];
            $unitList = [];
        }

        return view('admin.dashboard.staff.index', compact('staff', 'jabatanList', 'unitList'));
    }

    public function create()
    {
        return view('admin.dashboard.staff.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:18|unique:staff,nip',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'nip' => trim($request->nip),
                'nama_lengkap' => trim($request->nama_lengkap),
                'jabatan' => trim($request->jabatan),
                'unit_kerja' => trim($request->unit_kerja)
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Staff berhasil ditambahkan.');
                return redirect()->route('admin.staff');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan staff.']);

        } catch (\Exception $e) {
            Log::error('Store Staff Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan staff.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            // Get staff data
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                $result = $response->json();
                $staff = $result['data'] ?? null;

                if ($staff) {
                    return view('admin.dashboard.staff.form', compact('staff'));
                }
            }

            session()->flash('error', 'Staff tidak ditemukan.');
            return redirect()->route('admin.staff');

        } catch (\Exception $e) {
            Log::error('Edit Staff Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data staff.');
            return redirect()->route('admin.staff');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required|string|max:18|unique:staff,nip,' . $id,
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'nip' => trim($request->nip),
                'nama_lengkap' => trim($request->nama_lengkap),
                'jabatan' => trim($request->jabatan),
                'unit_kerja' => trim($request->unit_kerja)
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Staff berhasil diperbarui.');
                return redirect()->route('admin.staff');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui staff.']);

        } catch (\Exception $e) {
            Log::error('Update Staff Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui staff.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Staff berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus staff.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Staff Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus staff.');
        }

        return redirect()->route('admin.staff');
    }

    private function getJabatanList($staff)
    {
        $jabatanList = [];

        if ($staff instanceof LengthAwarePaginator) {
            foreach ($staff->items() as $item) {
                if (isset($item['jabatan'])) {
                    $jabatanList[] = $item['jabatan'];
                }
            }
        } else {
            foreach ($staff as $item) {
                if (isset($item['jabatan'])) {
                    $jabatanList[] = $item['jabatan'];
                }
            }
        }

        return array_unique($jabatanList);
    }

    private function getUnitKerjaList($staff)
    {
        $unitList = [];

        if ($staff instanceof LengthAwarePaginator) {
            foreach ($staff->items() as $item) {
                if (isset($item['unit_kerja'])) {
                    $unitList[] = $item['unit_kerja'];
                }
            }
        } else {
            foreach ($staff as $item) {
                if (isset($item['unit_kerja'])) {
                    $unitList[] = $item['unit_kerja'];
                }
            }
        }

        return array_unique($unitList);
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

    private function createManualPagination($allStaff, $request)
    {
        $allStaff = is_array($allStaff) ? $allStaff : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allStaff = array_filter($allStaff, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_lengkap']), $searchTerm) !== false ||
                       strpos(strtolower($item['nip']), $searchTerm) !== false ||
                       strpos(strtolower($item['jabatan'] ?? ''), $searchTerm) !== false ||
                       strpos(strtolower($item['unit_kerja'] ?? ''), $searchTerm) !== false;
            });
        }

        // Apply jabatan filter
        if ($request->filled('jabatan_filter')) {
            $jabatan = $request->jabatan_filter;
            $allStaff = array_filter($allStaff, function ($item) use ($jabatan) {
                return strtolower($item['jabatan']) == strtolower($jabatan);
            });
        }

        // Apply unit kerja filter
        if ($request->filled('unit_filter')) {
            $unit = $request->unit_filter;
            $allStaff = array_filter($allStaff, function ($item) use ($unit) {
                return strtolower($item['unit_kerja']) == strtolower($unit);
            });
        }

        $total = count($allStaff);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allStaff, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}