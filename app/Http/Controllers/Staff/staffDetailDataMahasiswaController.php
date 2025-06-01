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
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Fetch student data from API based on nim
        $studentData = $this->getApiData("/api/enrollment-mahasiswa/{$nim}", [], $token);
        if (!$studentData) {
            return redirect()->route('staff-keuangan.data-mahasiswa')->withErrors(['error' => 'Data mahasiswa tidak ditemukan.']);
        }

        // Fetch payment data for the student
        $paymentData = $this->getApiData("/api/pembayaran-ukt-semester", ['nim' => $nim], $token);

        // Fetch programs (prodi) and faculties (jurusan)
        $programs = $this->getApiData('/api/kelas', [], $token);
        $faculties = $this->getApiData('/api/fakultas', [], $token);

        // Map program and faculty names for quick lookup
        $programsMap = collect($programs)->pluck('program_studi.nama_prodi', 'id')->toArray();
        $facultiesMap = collect($faculties)->pluck('nama_fakultas', 'id')->toArray();

        // Add program and faculty names to student data
        $studentData['kelas']['program_name'] = $programsMap[$studentData['kelas']['id_prodi']] ?? 'Unknown Program';
        $facultyId = collect($programs)->firstWhere('id', $studentData['kelas']['id_prodi'])['program_studi']['id_fakultas'] ?? null;
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
