<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StaffDetailBeasiswaController extends Controller
{
    public function index($nim, Request $request)
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

        // Fetch beasiswa details by nim (using NIM instead of id)
        $beasiswaData = $this->getApiData("/api/penerima-beasiswa", ['nim' => $nim], $token);

        if (empty($beasiswaData)) {
            return redirect()->route('staff-keuangan.beasiswa.staff-beasiswa')->withErrors(['error' => 'Data beasiswa tidak ditemukan.']);
        }

        // Check if the "mahasiswa" key exists in the beasiswa data before accessing
        if (!isset($beasiswaData[0]['mahasiswa'])) {
            return redirect()->route('staff-keuangan.beasiswa.staff-beasiswa')->withErrors(['error' => 'Data mahasiswa tidak ditemukan.']);
        }

        // Fetch enrollment mahasiswa data (program studi, angkatan, dsb)
        $enrollmentData = $this->getApiData("/api/enrollment-mahasiswa", ['nim' => $nim], $token);

        if (empty($enrollmentData)) {
            return redirect()->route('staff-keuangan.beasiswa.staff-beasiswa')->withErrors(['error' => 'Data enrollment mahasiswa tidak ditemukan.']);
        }

        // Combine the beasiswa data with enrollment data
        $beasiswaData[0]['mahasiswa'] = $enrollmentData[0]['mahasiswa'] ?? null;
        $beasiswaData[0]['program_studi'] = $enrollmentData[0]['program_studi'] ?? null;
        $beasiswaData[0]['tahun_akademik'] = $enrollmentData[0]['tahun_akademik'] ?? null;

        // Parse the dates using Carbon
        $beasiswaData[0]['tanggal_mulai'] = Carbon::createFromFormat('Y-m-d H:i:s', $beasiswaData[0]['tanggal_mulai'])->translatedFormat('l d F Y');
        $beasiswaData[0]['tanggal_selesai'] = Carbon::createFromFormat('Y-m-d H:i:s', $beasiswaData[0]['tanggal_selesai'])->translatedFormat('l d F Y');

        // Fetch payment data for the beasiswa if necessary
        $paymentData = $this->getApiData("/api/pembayaran-ukt-semester", ['nim' => $beasiswaData[0]['mahasiswa']['nim']], $token);
        
        // Combine payment data to beasiswaData if available
        $beasiswaData[0]['payment'] = $paymentData ?? null;

        // Return the view with the data
        return view('staff-keuangan.beasiswa.staff-detail-beasiswa', compact('beasiswaData'));
    }

    // Method to fetch data from API
    private function getApiData($endpoint, $queryParams = [], $token)
    {
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . $endpoint, $queryParams);
            return $response->successful() ? $response->json()['data'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
