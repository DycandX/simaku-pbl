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
        $studentData = $this->getApiData("/api/enrollment-mahasiswa", ['nim' => $nim], $token);

        // Check if student data is empty or doesn't contain 'mahasiswa'
        if (empty($studentData) || !isset($studentData[0]['mahasiswa'])) {
            return redirect()->route('staff-keuangan.data-mahasiswa')->withErrors(['error' => 'Data mahasiswa tidak ditemukan.']);
        }

        // Fetch programs (prodi) and faculties (jurusan)
        $programs = $this->getApiData('/api/kelas', [], $token);
        $faculties = $this->getApiData('/api/fakultas', [], $token);

        // Map program and faculty names for quick lookup
        $programsMap = collect($programs)->pluck('program_studi.nama_prodi', 'id')->toArray();
        $facultiesMap = collect($faculties)->pluck('nama_fakultas', 'id')->toArray();

        // Add program and faculty names to student data
        if (isset($studentData[0]['kelas'])) {
            $studentData[0]['kelas']['program_name'] = $programsMap[$studentData[0]['kelas']['id_prodi']] ?? 'Unknown Program';

            // Ensure faculty data exists before assigning it
            $facultyId = collect($programs)->firstWhere('id', $studentData[0]['kelas']['id_prodi'])['program_studi']['id_fakultas'] ?? null;
            $studentData[0]['kelas']['faculty_name'] = $facultiesMap[$facultyId] ?? 'Unknown Faculty';
        }

        // Fetch payment data for the student
        $paymentData = $this->getApiData("/api/pembayaran-ukt-semester", ['nim' => $nim], $token);

        // Check if 'ukt_semester' exists in student data
        if (isset($studentData[0]['ukt_semester'])) {
            $studentData[0]['ukt_semester'] = $studentData[0]['ukt_semester'];  // If available, you can pass it to view
        } else {
            $studentData[0]['ukt_semester'] = null;  // Provide default value if not available
        }

        // Fetch payment details to get 'metode_pembayaran'
        $paymentDetails = $this->getApiData("/api/detail-pembayaran", ['nim' => $nim], $token);

        // Add payment method to payment data if available
        if (!empty($paymentDetails) && isset($paymentDetails[0]['metode_pembayaran'])) {
            $paymentData[0]['metode_pembayaran'] = $paymentDetails[0]['metode_pembayaran'];
        } else {
            $paymentData[0]['metode_pembayaran'] = 'Tidak Tersedia'; // Default if not found
        }

        // Add 'tahun_akademik' to student data
        if (isset($studentData[0]['tahun_akademik'])) {
            $studentData[0]['tahun_akademik'] = $studentData[0]['tahun_akademik']['tahun_akademik'] ?? 'Unknown Academic Year';
        } else {
            $studentData[0]['tahun_akademik'] = 'Unknown Academic Year';
        }

        return view('staff-keuangan.data-mahasiswa.detail-data-mahasiswa', compact('studentData', 'paymentData'));
    }

    // Method to handle API calls and get data
    private function getApiData($endpoint, $queryParams = [], $token)
    {
        try {
            // Send GET request to the API with the token and query parameters
            $response = Http::withToken($token)->get(config('app.api_url') . $endpoint, $queryParams);

            // Check if the response is successful, otherwise return an empty array
            return $response->successful() ? optional($response->json())['data'] ?? [] : [];
        } catch (\Exception $e) {
            // Handle error and return empty array in case of any exception
            return [];
        }
    }
}
