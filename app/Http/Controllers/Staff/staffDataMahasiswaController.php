<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class StaffDataMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in and has valid session
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Get filtering parameters from the request
        $angkatan = $request->get('angkatan', '');
        $prodi = $request->get('prodi', '');
        $searchTerm = $request->get('search', '');

        // Build query parameters
        $queryParams = [
            'angkatan' => $angkatan,
            'prodi' => $prodi,
            'search' => $searchTerm
        ];

        // Fetch student data from API
        $students = $this->getApiData('/api/enrollment-mahasiswa', $queryParams, $token);
        dd($students);

        // Fetch programs (prodi) and faculties (jurusan) data
        $programs = $this->getApiData('/api/kelas', [], $token);
        $faculties = $this->getApiData('/api/fakultas', [], $token);

        // Create a map of program id to program name for quick lookup
        $programsMap = collect($programs)->pluck('program_studi.nama_prodi', 'id')->toArray();

        // Create a map of faculty id to faculty name for quick lookup
        $facultiesMap = collect($faculties)->pluck('nama_fakultas', 'id')->toArray();

        // Map the program and faculty names to the students' data
        foreach ($students as &$student) {
            // Add program name
            $student['kelas']['program_name'] = $programsMap[$student['kelas']['id_prodi']] ?? 'Unknown Program';

            // Add faculty name (based on the id_fakultas from program studi)
            $programId = $student['kelas']['id_prodi'];
            $facultyId = collect($programs)->firstWhere('id', $programId)['program_studi']['id_fakultas'] ?? null; // Get faculty id from program
            $student['kelas']['faculty_name'] = $facultiesMap[$facultyId] ?? 'Unknown Faculty';
        }

        // Fetch years (tahun akademik)
        $years = $this->getApiData('/api/tahun-akademik', [], $token);

        return view('staff-keuangan.data-mahasiswa.data-mahasiswa', compact('students', 'programs', 'years', 'angkatan', 'prodi', 'searchTerm', 'facultiesMap'));
    }

    private function getApiData($endpoint, $queryParams = [], $token)
    {
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . $endpoint, $queryParams);
            return $response->successful() ? optional($response->json())['data'] ?? [] : [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
