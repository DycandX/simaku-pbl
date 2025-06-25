<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CekTagihanUktController extends Controller
{
    public function index(Request $request)
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

        // Ambil semua data dari API
        $allDataTagihan = $this->getApiData('/api/ukt-semester', [], $token);

        // Pagination manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5; // jumlah data per halaman
        $offset = ($currentPage - 1) * $perPage;
        $dataSlice = array_slice($allDataTagihan, $offset, $perPage);

        $dataTagihan = new LengthAwarePaginator(
            $dataSlice,
            count($allDataTagihan),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $periodePembayaran = $this->getApiData("/api/periode-pembayaran", [], $token);
        $programStudi = $this->getApiData("/api/program-studi", [], $token);

        // Hitung total lunas dan belum lunas
        $totalSudahLunas = 0;
        $totalBelumLunas = 0;

        foreach ($allDataTagihan as $ukt) {
            $jumlahUkt = isset($ukt['jumlah_ukt']) ? (float) $ukt['jumlah_ukt'] : 0;
            $totalDibayar = 0;

            if (!empty($ukt['pembayaran']) && is_array($ukt['pembayaran'])) {
                foreach ($ukt['pembayaran'] as $pembayaran) {
                    if (isset($pembayaran['status']) && $pembayaran['status'] === 'terbayar') {
                        $nominal = isset($pembayaran['nominal_tagihan']) ? (float) $pembayaran['nominal_tagihan'] : 0;
                        $totalDibayar += $nominal;
                    }
                }
            }

            if ($totalDibayar >= $jumlahUkt && $jumlahUkt > 0) {
                $totalSudahLunas++;
            } else {
                $totalBelumLunas++;
            }
        }

        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt', [
            'dataTagihan' => $dataTagihan,
            'periodePembayaran' => $periodePembayaran,
            'programStudi' => $programStudi,
            'totalSemuaTagihan' => count($allDataTagihan),
            'totalSudahLunas' => $totalSudahLunas,
            'totalBelumLunas' => $totalBelumLunas,
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
        // Ambil data periode pembayaran dari API
        $periodePembayaran = $this->getApiData("/api/periode-pembayaran", [], $token);

        // Ambil data program studi dari API
        $programStudi = $this->getApiData("/api/program-studi", [], $token);
        //dd($detailTagihan);
        //dd($periodePembayaran);
        //dd($programStudi);
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
