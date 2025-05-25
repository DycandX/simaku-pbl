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
        if (!$userData && !$token) {
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

        // Fetch programs (prodi) and academic year (angkatan) for dropdown filters
        $programs = $this->getApiData('/api/program-studi', [], $token);
        $years = $this->getApiData('/api/tahun-akademik', [], $token);

        // Create a map of program id to program name for quick lookup in the view
        $programsMap = collect($programs)->pluck('nama_prodi', 'id')->toArray();

        // Map the program names to the students' data
        foreach ($students as &$student) {
            // Replace the id_prodi with the program name (if available)
            $student['kelas']['program_name'] = $programsMap[$student['kelas']['id_prodi']];
            //dd ($student);
        }

        return view('staff-keuangan.data-mahasiswa.data-mahasiswa', compact('students', 'programs', 'years', 'programsMap', 'angkatan', 'prodi', 'searchTerm'));
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
