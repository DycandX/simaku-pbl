<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;

class PembayaranUktStaffController extends Controller
{
    public function index(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Ambil semua data detail pembayaran dari API
        $allDetailPembayaran = $this->getApiData('/api/detail-pembayaran', [], $token);

        // Ambil data tambahan untuk dropdown/filter
        $periodePembayaran = $this->getApiData("/api/periode-pembayaran", [], $token);
        $programStudi = $this->getApiData("/api/program-studi", [], $token);

        // Pagination manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $offset = ($currentPage - 1) * $perPage;
        $dataSlice = array_slice($allDetailPembayaran, $offset, $perPage);

        $detailPembayaranData = new LengthAwarePaginator(
            $dataSlice,
            count($allDetailPembayaran),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Hitung summary status
        $statusSummary = [
            'diverifikasi' => collect($allDetailPembayaran)->where('status', 'verified')->count(),
            'belum_diverifikasi' => collect($allDetailPembayaran)->where('status', 'pending')->count(),
            'ditolak' => collect($allDetailPembayaran)->where('status', 'rejected')->count(),
        ];
        $totalNominalTerverifikasi = collect($allDetailPembayaran)
            ->where('status', 'verified')
            ->sum(function ($item) {
                return (float) $item['nominal'];
            });

        //dd($detailPembayaranData);
        //dd($statusSummary);
        //dd($periodePembayaran);
        //dd($programStudi);
        return view('staff-keuangan.dashboard.pembayaran-ukt.pembayaran-ukt', [
            'detailPembayaranData' => $detailPembayaranData,
            'statusSummary' => $statusSummary,
            'periodePembayaran' => $periodePembayaran,
            'programStudi' => $programStudi,
            'totalNominalTerverifikasi' => $totalNominalTerverifikasi,
        ]);
    }

    public function show($id)
{
    $userData = Session::get('user_data');
    $token = Session::get('token');

    if (!$userData || !$token) {
        return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
    }

    if (!in_array($userData['role'], ['admin', 'staff'])) {
        return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
    }

    // Ambil detail pembayaran
    $detailPembayaran = $this->getApiData("/api/detail-pembayaran/{$id}", [], $token);
    if (empty($detailPembayaran)) {
        return redirect()->route('staff.pembayaran-ukt.index')->withErrors(['error' => 'Data tidak ditemukan.']);
    }

    // Ambil jenis pembayaran (langsung array, bukan ['data' => []])
    $jenisPembayaranList = $this->getApiData("/api/jenis-pembayaran", [], $token);

    $namaJenisPembayaran = '-';
    if (
        isset($detailPembayaran['pembayaran_ukt_semester']['id_jenis_pembayaran']) &&
        is_array($jenisPembayaranList)
    ) {
        $idJenisPembayaran = $detailPembayaran['pembayaran_ukt_semester']['id_jenis_pembayaran'];

        foreach ($jenisPembayaranList as $jenis) {
            if ((int)$jenis['id'] === (int)$idJenisPembayaran) {
                $namaJenisPembayaran = $jenis['nama_jenis'];
                break;
            }
        }
    }

    return view('staff-keuangan.dashboard.pembayaran-ukt.detail-pembayaran-ukt', [
        'detailPembayaran' => $detailPembayaran,
        'namaJenisPembayaran' => $namaJenisPembayaran
    ]);
}



    public function updateStatus(Request $request, $id)
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Token tidak ditemukan, harap login ulang.']);
        }

        $status = $request->input('status');

        // Validasi status
        if (!in_array($status, ['verified', 'rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Status tidak valid.']);
        }

        try {
            $response = Http::withToken($token)->put(config('app.api_url') . "/api/detail-pembayaran/{$id}", [
                'status' => $status
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Gagal memperbarui status pembayaran.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui status.']);
        }
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
