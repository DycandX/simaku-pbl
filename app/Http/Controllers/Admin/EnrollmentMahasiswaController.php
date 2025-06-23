<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class EnrollmentMahasiswaController extends Controller
{
    private $apiBaseUrl;
    private $mahasiswaApiUrl;
    private $prodiApiUrl;
    private $golonganUktApiUrl;
    private $kelasApiUrl;
    private $tingkatApiUrl;
    private $tahunAkademikApiUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_url') . '/api/enrollment-mahasiswa';
        $this->mahasiswaApiUrl = config('app.api_url') . '/api/mahasiswa';
        $this->prodiApiUrl = config('app.api_url') . '/api/program-studi';
        $this->golonganUktApiUrl = config('app.api_url') . '/api/golongan-ukt';
        $this->kelasApiUrl = config('app.api_url') . '/api/kelas';
        $this->tingkatApiUrl = config('app.api_url') . '/api/tingkat';
        $this->tahunAkademikApiUrl = config('app.api_url') . '/api/tahun-akademik';
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
                $query['id_program_studi'] = $request->prodi_filter;
            }

            if ($request->filled('kelas_filter')) {
                $query['id_kelas'] = $request->kelas_filter;
            }

            if ($request->filled('tingkat_filter')) {
                $query['id_tingkat'] = $request->tingkat_filter;
            }

            if ($request->filled('tahun_akademik_filter')) {
                $query['id_tahun_akademik'] = $request->tahun_akademik_filter;
            }

            if ($request->filled('page')) {
                $query['page'] = $request->page;
            }

            // Get Enrollment Mahasiswa data
            $response = Http::withToken($token)->get($this->apiBaseUrl, $query);

            // Get filter data
            $filterData = $this->getFilterData($token);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['data']) && is_array($result['data'])) {
                    $enrollmentData = $this->mapRelationNames($result['data']);
                    $result['data'] = $enrollmentData;
                    $enrollments = $this->createPaginationObject($result, $request);
                } else {
                    $allEnrollments = $result['data'] ?? $result;
                    $allEnrollments = $this->mapRelationNames($allEnrollments);
                    $enrollments = $this->createManualPagination($allEnrollments, $request);
                }
            } else {
                Log::error('Enrollment Mahasiswa API Error', ['status' => $response->status(), 'body' => $response->body()]);
                session()->flash('error', 'Gagal mengambil data enrollment mahasiswa dari server.');
                $enrollments = collect([]);
            }

        } catch (\Exception $e) {
            Log::error('EnrollmentMahasiswaController Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data enrollment mahasiswa.');
            $enrollments = collect([]);
            $filterData = [
                'prodiList' => [],
                'kelasList' => [],
                'tingkatList' => [],
                'tahunAkademikList' => []
            ];
        }

        return view('admin.dashboard.enrollment-mahasiswa.index', compact('enrollments') + $filterData);
    }

    public function create()
    {
        $token = Session::get('token');

        try {
            // Get all required data for form dropdowns
            $formData = $this->getFormData($token);

            return view('admin.dashboard.enrollment-mahasiswa.form', $formData);
        } catch (\Exception $e) {
            Log::error('Create Enrollment Mahasiswa Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memuat form.');
            return redirect()->route('admin.enrollment-mahasiswa');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_program_studi' => 'required|integer',
            'id_golongan_ukt' => 'required|integer',
            'id_kelas' => 'required|integer',
            'id_tingkat' => 'required|integer',
            'id_tahun_akademik' => 'required|integer',
        ]);

        $token = Session::get('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        try {
            $data = [
                'id_mahasiswa' => $request->id_mahasiswa,
                'id_program_studi' => $request->id_program_studi,
                'id_golongan_ukt' => $request->id_golongan_ukt,
                'id_kelas' => $request->id_kelas,
                'id_tingkat' => $request->id_tingkat,
                'id_tahun_akademik' => $request->id_tahun_akademik
            ];

            $response = Http::withToken($token)->post($this->apiBaseUrl, $data);

            if ($response->successful()) {
                session()->flash('success', 'Enrollment mahasiswa berhasil ditambahkan.');
                return redirect()->route('admin.enrollment-mahasiswa');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal menambahkan enrollment mahasiswa.']);

        } catch (\Exception $e) {
            Log::error('Store Enrollment Mahasiswa Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat menambahkan enrollment mahasiswa.']);
        }
    }

    public function edit($id)
    {
        $token = Session::get('token');

        try {
            // Get enrollment data
            $response = Http::withToken($token)->get($this->apiBaseUrl . '/' . $id);

            // Get form data for dropdowns
            $formData = $this->getFormData($token);

            if ($response->successful()) {
                $result = $response->json();
                $enrollment = $result['data'] ?? null;

                if ($enrollment) {
                    // Map relation names
                    $enrollment = $this->mapRelationNames([$enrollment])[0];
                    return view('admin.dashboard.enrollment-mahasiswa.form', compact('enrollment') + $formData);
                }
            }

            session()->flash('error', 'Enrollment mahasiswa tidak ditemukan.');
            return redirect()->route('admin.enrollment-mahasiswa');

        } catch (\Exception $e) {
            Log::error('Edit Enrollment Mahasiswa Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat mengambil data enrollment mahasiswa.');
            return redirect()->route('admin.enrollment-mahasiswa');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|integer',
            'id_program_studi' => 'required|integer',
            'id_golongan_ukt' => 'required|integer',
            'id_kelas' => 'required|integer',
            'id_tingkat' => 'required|integer',
            'id_tahun_akademik' => 'required|integer',
        ]);

        $token = Session::get('token');

        try {
            $data = [
                'id_mahasiswa' => $request->id_mahasiswa,
                'id_program_studi' => $request->id_program_studi,
                'id_golongan_ukt' => $request->id_golongan_ukt,
                'id_kelas' => $request->id_kelas,
                'id_tingkat' => $request->id_tingkat,
                'id_tahun_akademik' => $request->id_tahun_akademik
            ];

            $response = Http::withToken($token)->put($this->apiBaseUrl . '/' . $id, $data);

            if ($response->successful()) {
                session()->flash('success', 'Enrollment mahasiswa berhasil diperbarui.');
                return redirect()->route('admin.enrollment-mahasiswa');
            }

            $error = $response->json();
            return back()->withInput()->withErrors($error['errors'] ?? ['api_error' => $error['message'] ?? 'Gagal memperbarui enrollment mahasiswa.']);

        } catch (\Exception $e) {
            Log::error('Update Enrollment Mahasiswa Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['api_error' => 'Terjadi kesalahan saat memperbarui enrollment mahasiswa.']);
        }
    }

    public function destroy($id)
    {
        $token = Session::get('token');

        try {
            $response = Http::withToken($token)->delete($this->apiBaseUrl . '/' . $id);

            if ($response->successful()) {
                session()->flash('success', 'Enrollment mahasiswa berhasil dihapus.');
            } else {
                $error = $response->json();
                session()->flash('error', $error['message'] ?? 'Gagal menghapus enrollment mahasiswa.');
            }

        } catch (\Exception $e) {
            Log::error('Delete Enrollment Mahasiswa Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menghapus enrollment mahasiswa.');
        }

        return redirect()->route('admin.enrollment-mahasiswa');
    }

    private function getFilterData($token)
    {
        $prodiList = $this->getApiData($this->prodiApiUrl, $token);
        $kelasList = $this->getApiData($this->kelasApiUrl, $token);
        $tingkatList = $this->getApiData($this->tingkatApiUrl, $token);
        $tahunAkademikList = $this->getApiData($this->tahunAkademikApiUrl, $token);

        return [
            'prodiList' => $prodiList,
            'kelasList' => $kelasList,
            'tingkatList' => $tingkatList,
            'tahunAkademikList' => $tahunAkademikList
        ];
    }

    private function getFormData($token)
    {
        $mahasiswaList = $this->getApiData($this->mahasiswaApiUrl, $token);
        $prodiList = $this->getApiData($this->prodiApiUrl, $token);
        $golonganUktList = $this->getApiData($this->golonganUktApiUrl, $token);
        $kelasList = $this->getApiData($this->kelasApiUrl, $token);
        $tingkatList = $this->getApiData($this->tingkatApiUrl, $token);
        $tahunAkademikList = $this->getApiData($this->tahunAkademikApiUrl, $token);

        return [
            'mahasiswaList' => $mahasiswaList,
            'prodiList' => $prodiList,
            'golonganUktList' => $golonganUktList,
            'kelasList' => $kelasList,
            'tingkatList' => $tingkatList,
            'tahunAkademikList' => $tahunAkademikList
        ];
    }

    private function getApiData($url, $token)
    {
        try {
            $response = Http::withToken($token)->get($url);
            if ($response->successful()) {
                $result = $response->json();
                return $result['data'] ?? $result;
            }
        } catch (\Exception $e) {
            Log::error('API Data Error: ' . $e->getMessage(), ['url' => $url]);
        }
        return [];
    }

    private function mapRelationNames($enrollmentData)
    {
        foreach ($enrollmentData as &$enrollment) {
            // Map mahasiswa name and NIM
            if (isset($enrollment['mahasiswa'])) {
                $enrollment['nama_mahasiswa'] = $enrollment['mahasiswa']['nama_lengkap'];
                $enrollment['nim'] = $enrollment['mahasiswa']['nim'];
            }

            // Map program studi name
            if (isset($enrollment['program_studi'])) {
                $enrollment['nama_prodi'] = $enrollment['program_studi']['nama_prodi'];
            }

            // Map golongan UKT info
            if (isset($enrollment['golongan_ukt'])) {
                $enrollment['golongan_ukt_info'] = 'Level ' . $enrollment['golongan_ukt']['level'] . ' - Rp ' . number_format($enrollment['golongan_ukt']['nominal'], 0, ',', '.');
            }

            // Map kelas name
            if (isset($enrollment['kelas'])) {
                $enrollment['nama_kelas'] = $enrollment['kelas']['nama_kelas'];
            }

            // Map tingkat name
            if (isset($enrollment['tingkat'])) {
                $enrollment['nama_tingkat'] = $enrollment['tingkat']['nama_tingkat'];
            }

            // Map tahun akademik
            if (isset($enrollment['tahun_akademik'])) {
                $enrollment['tahun_akademik_info'] = $enrollment['tahun_akademik']['tahun_akademik'] . ' - ' . $enrollment['tahun_akademik']['semester'];
            }
        }

        return $enrollmentData;
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

    private function createManualPagination($allEnrollments, $request)
    {
        $allEnrollments = is_array($allEnrollments) ? $allEnrollments : [];
        $perPage = 10;
        $currentPage = $request->get('page', 1);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $allEnrollments = array_filter($allEnrollments, function ($item) use ($searchTerm) {
                return strpos(strtolower($item['nama_mahasiswa'] ?? ''), $searchTerm) !== false ||
                       strpos(strtolower($item['nim'] ?? ''), $searchTerm) !== false ||
                       strpos(strtolower($item['nama_prodi'] ?? ''), $searchTerm) !== false ||
                       strpos(strtolower($item['nama_kelas'] ?? ''), $searchTerm) !== false;
            });
        }

        // Apply filters
        if ($request->filled('prodi_filter')) {
            $prodiId = $request->prodi_filter;
            $allEnrollments = array_filter($allEnrollments, function ($item) use ($prodiId) {
                return $item['id_program_studi'] == $prodiId;
            });
        }

        if ($request->filled('kelas_filter')) {
            $kelasId = $request->kelas_filter;
            $allEnrollments = array_filter($allEnrollments, function ($item) use ($kelasId) {
                return $item['id_kelas'] == $kelasId;
            });
        }

        if ($request->filled('tingkat_filter')) {
            $tingkatId = $request->tingkat_filter;
            $allEnrollments = array_filter($allEnrollments, function ($item) use ($tingkatId) {
                return $item['id_tingkat'] == $tingkatId;
            });
        }

        if ($request->filled('tahun_akademik_filter')) {
            $tahunAkademikId = $request->tahun_akademik_filter;
            $allEnrollments = array_filter($allEnrollments, function ($item) use ($tahunAkademikId) {
                return $item['id_tahun_akademik'] == $tahunAkademikId;
            });
        }

        $total = count($allEnrollments);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allEnrollments, $offset, $perPage);

        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
    }
}