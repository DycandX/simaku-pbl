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


        // Fetch beasiswa details by nim using the same API as the mahasiswa controller
        $beasiswa = $this->getApiData("/api/penerima-beasiswa?nim=" . urlencode($nim), $token);

        if (empty($beasiswa)) {
            return redirect()->route('staff-keuangan.beasiswa.staff-beasiswa')->withErrors(['error' => 'Data beasiswa tidak ditemukan.']);
        }

        //dd($beasiswa);
        return view('staff-keuangan.beasiswa.staff-detail-beasiswa', compact('beasiswa'));
    }

    private function getApiData($endpoint, $token)
    {
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . $endpoint);
            return $response->successful() ? optional($response->json())['data'] ?? [] : [];
        } catch (\Exception $e) {
            return [];
        }
    }
}