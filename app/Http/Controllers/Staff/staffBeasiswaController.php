<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class StaffBeasiswaController extends Controller
{
    public function index(Request $request)
    {
        // Get user data and token from session
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in and has a valid session
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Fetch penerima beasiswa data
        $beasiswaData = $this->getApiData("/api/penerima-beasiswa");

        // Fetch enrollment mahasiswa data (program studi, angkatan, dsb)
        $enrollmentData = $this->getApiData("/api/enrollment-mahasiswa");

        // Merging data penerima beasiswa and enrollment mahasiswa
        $data = [];

        foreach ($beasiswaData as $beasiswa) {
            // Find mahasiswa by nim
            $mahasiswa = $this->findMahasiswa($beasiswa['nim'], $enrollmentData);
            // Find program studi by id_prodi
            $program_studi = $this->findProgramStudi($mahasiswa['id_program_studi'], $enrollmentData);

            // Add mahasiswa and program_studi data to beasiswa data
            $beasiswa['mahasiswa'] = $mahasiswa;
            $beasiswa['program_studi'] = $program_studi;
            $data[] = $beasiswa;
        }

        // Paginate the merged data using Laravel pagination
        $data = collect($data); // Convert to collection
        $data = $data->forPage($request->page ?? 1, 10);  // Manually paginate, 10 items per page

        // Use LengthAwarePaginator to paginate
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $data,
            count($data),
            10,
            $request->page ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('staff-keuangan.beasiswa.staff-beasiswa', compact('paginatedData'));
    }

    // Method to fetch data from API
    private function getApiData($endpoint)
    {
        try {
            $response = Http::get(config('app.api_url') . $endpoint);
            return $response->successful() ? optional($response->json())['data'] ?? [] : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    // Method to find mahasiswa by NIM
    private function findMahasiswa($nim, $enrollmentData)
    {
        foreach ($enrollmentData as $data) {
            if ($data['mahasiswa']['nim'] == $nim) {
                return $data['mahasiswa'];
            }
        }
        return null;
    }

    // Method to find program studi by id_prodi
    private function findProgramStudi($id_prodi, $enrollmentData)
    {
        foreach ($enrollmentData as $data) {
            if ($data['program_studi']['id'] == $id_prodi) {
                return $data['program_studi'];
            }
        }
        return null;
    }
}
