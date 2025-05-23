<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class LihatTagihanUktController extends Controller
{
    public function index()
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Jika belum ada user data tapi token tersedia
        if (!$userData && $token) {
            try {
                $response = Http::withToken($token)->get(config('app.api_url') . '/api/user');
                if ($response->successful()) {
                    $allUsers = optional($response->json())['data'] ?? [];
                    $username = Session::get('username');
                    
                    foreach ($allUsers as $user) {
                        if ($user['username'] === $username) {
                            $userData = $user;
                            Session::put('user_data', $userData);
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('login')->withErrors(['error' => 'Sesi telah berakhir. Silakan login kembali.']);
            }
        }

        // Jika tidak ada data user, redirect ke login
        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil NIM dari user yang login
        $nim =  Session::get('username');

        // Ambil semua data dari API
        $uktSemester = $this->getApiData("/api/ukt-semester?nim=" . urlencode($nim), $token);
        //dd($uktSemester);
        $pembayaran = $this->getApiData("/api/pembayaran-ukt-semester?nim=" . urlencode($nim), $token);
        //dd($pembayaran);
        $detailPembayaran = $this->getApiData("/api/detail-pembayaran?nim=" . urlencode($nim), $token);
        //dd($detailPembayaran);
        return view('mahasiswa.dashboard.tagihan-ukt.lihat_tagihan_ukt', compact('userData', 'uktSemester', 'pembayaran', 'detailPembayaran'));

    }

    public function show($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil data detail tagihan berdasarkan ID
        $pembayaranUkt = $this->getApiData("/api/pembayaran-ukt-semester/$id", $token);
        //dd($pembayaranUkt);
        $detailPembayaran = $this->getApiData("/api/detail-pembayaran/$id", $token);
        //dd($detailPembayaran);
        if (empty($pembayaranUkt) && empty($detailPembayaran)) {
            return redirect()->back()->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
        }

        return view('mahasiswa.dashboard.tagihan-ukt.detail_tagihan_ukt', compact('pembayaranUkt', 'detailPembayaran', 'userData'));
        
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
    // private function getApiData($endpoint, $token)
    // {
    //     try {
    //         $response = Http::withToken($token)->get(config('app.api_url') . $endpoint);
    //         if ($response->successful()) {
    //             return $response->json(); // Jangan langsung ambil ['data'], biar kelihatan struktur aslinya
    //         }
    //         return [];
    //     } catch (\Exception $e) {
    //         return [];
    //     }
    // }

}