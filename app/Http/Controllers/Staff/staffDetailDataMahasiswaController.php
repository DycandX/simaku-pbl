<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class StaffDetailDataMahasiswaController extends Controller
{
    public function index(Request $request, $nim)
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

        // Fetch student data from API based on nim
        $studentData = $this->getApiData("/api/enrollment-mahasiswa/{$nim}", [], $token);
        $paymentData = $this->getApiData("/api/pembayaran-ukt-semester", ['nim' => $nim], $token);

        // If no student data is found
        if (!$studentData) {
            return redirect()->route('staff-keuangan.data-mahasiswa')->withErrors(['error' => 'Data mahasiswa tidak ditemukan.']);
        }

        // Fetch programs (prodi) and academic year (angkatan) for dropdown filters
        $programs = $this->getApiData('/api/program-studi', [], $token);
        $faculties = $this->getApiData('/api/fakultas', [], $token);

        // Create a map of program id to program name for quick lookup
        $programsMap = collect($programs)->pluck('nama_prodi', 'id')->toArray();
        $facultiesMap = collect($faculties)->pluck('nama_fakultas', 'id')->toArray();

        // Add program and faculty names to student data
        $studentData['kelas']['program_name'] = $programsMap[$studentData['kelas']['id_prodi']] ?? 'Unknown Program';
        $facultyId = $programsMap[$studentData['kelas']['id_prodi']] ?? null;
        $studentData['kelas']['faculty_name'] = $facultiesMap[$facultyId] ?? 'Unknown Faculty';

        return view('staff-keuangan.data-mahasiswa.detail-data-mahasiswa', compact('studentData', 'paymentData'));
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
