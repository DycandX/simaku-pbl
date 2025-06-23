<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class CekTagihanUktController extends Controller
{
    public function index()
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Fetch data from API
        $dataTagihan  = $this->getApiData('/api/ukt-semester', [], $token);
        //dd($dataTagihan);

        // Kirim data ke view
        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt', [
            'dataTagihan' => $dataTagihan,
        ]);
    }
    
    public function detail($idUkt)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');
        //dd($idUkt);

        // Check if user is logged in
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Fetch detail tagihan dari API berdasarkan ID UKT
        $detailTagihan = $this->getApiData("/api/ukt-semester/{$idUkt}", [], $token);

        if (!$detailTagihan) {
            return redirect()->route('staff.cek-tagihan-ukt.index')->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
        }
        //dd($detailTagihan);
        // Kirim data ke view detail
        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt-detail', [
            'detailTagihan' => $detailTagihan,
        ]);
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
