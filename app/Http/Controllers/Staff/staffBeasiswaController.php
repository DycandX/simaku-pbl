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

        // Get filtering parameters from the request
        $searchTerm = $request->get('search', '');  // Provide default empty value
        $prodi = $request->get('prodi', '');        // Provide default empty value

        // Build query parameters for filtering
        $queryParams = [
            'search' => $searchTerm,
            'prodi' => $prodi,
        ];

        // Fetch penerima beasiswa data
        $beasiswaData = $this->getApiData("/api/penerima-beasiswa", $queryParams, $token);
        // Fetch enrollment mahasiswa data (program studi)
        $enrollmentData = $this->getApiData("/api/enrollment-mahasiswa", $queryParams, $token);

        // Merging data penerima beasiswa and enrollment mahasiswa
        $data = [];

        foreach ($beasiswaData as $beasiswa) {
            // Get the ID of the mahasiswa from the beasiswa data
            $mahasiswaId = $beasiswa['mahasiswa']['id'];

            // Filter enrollmentData to only include the student matching the mahasiswaId
            $filteredEnrollment = collect($enrollmentData)->filter(function ($enrollment) use ($mahasiswaId) {
                return $enrollment['mahasiswa']['id'] == $mahasiswaId;
            })->first(); // Use first() to get the first matching entry

            // If there's no matching enrollment, skip this beasiswa
            if (!$filteredEnrollment) {
                continue;
            }

            // Get the relevant program studi details
            $mahasiswa = $filteredEnrollment['mahasiswa'];
            $program_studi = $filteredEnrollment['program_studi'];

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

        // Fetch available programs (prodi) data
        $programs = $this->getApiData('/api/program-studi', [], $token);

        // Create a map of program id to program name for quick lookup
        $programsMap = collect($programs)->pluck('nama_prodi', 'id')->toArray();

        // Add program name to each beasiswa
        foreach ($paginatedData as &$beasiswa) {
            $beasiswa['program_studi_name'] = $programsMap[$beasiswa['program_studi']['id']] ?? 'Unknown Program';
        }

        return view('staff-keuangan.beasiswa.staff-beasiswa', compact('paginatedData', 'searchTerm', 'prodi', 'programsMap'));
    }

    // Method to fetch data from API
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
