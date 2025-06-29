<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PengajuanCicilanStaffController extends Controller
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

        // Ambil semua data pengajuan cicilan dari API
        $allPengajuanCicilan = $this->getApiData('/api/pengajuan-cicilan', [], $token);

        $periodePembayaran = $this->getApiData("/api/periode-pembayaran", [], $token);
        $programStudi = $this->getApiData("/api/program-studi", [], $token);

        // Pagination manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $offset = ($currentPage - 1) * $perPage;
        $dataSlice = array_slice($allPengajuanCicilan, $offset, $perPage);

        $pengajuanData = new LengthAwarePaginator(
            $dataSlice,
            count($allPengajuanCicilan),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Hitung summary status
        $statusSummary = [
            'diverifikasi' => collect($allPengajuanCicilan)->where('status', 'approved')->count(),
            'belum_diverifikasi' => collect($allPengajuanCicilan)->where('status', 'pending')->count(),
            'ditolak' => collect($allPengajuanCicilan)->where('status', 'rejected')->count(),
        ];

        // Data untuk dropdown filter
        $semesterOptions = collect($allPengajuanCicilan)->pluck('semester')->unique()->values();
        $jurusanOptions = collect($allPengajuanCicilan)->pluck('jurusan')->unique()->values();
        $prodiOptions = collect($allPengajuanCicilan)->pluck('prodi')->unique()->values();

        return view('staff-keuangan.dashboard.pengajuan-cicilan.pengajuan-cicilan', [
            'pengajuanData' => $pengajuanData,
            'periodePembayaran' => $periodePembayaran,
            'programStudi' => $programStudi,
            'statusSummary' => $statusSummary,
            'semesterOptions' => $semesterOptions,
            'jurusanOptions' => $jurusanOptions,
            'prodiOptions' => $prodiOptions,
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

        $data = $this->getApiData("/api/pengajuan-cicilan/{$id}", [], $token);

        if (empty($data)) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data pengajuan.']);
        }

        $detailPengajuanCicilan = [
            'id' => $id,
            'AngsuranDiajukan' => $data['jumlah_angsuran_diajukan'],
            'AngsuranDisetujui' => $data['jumlah_angsuran_disetujui'],
            'status' => ucfirst($data['status']),
            'file_path' => $data['file_path'] ?? null,
            'nama' => $data['enrollment']['mahasiswa']['nama_lengkap'],
            'nim' => $data['enrollment']['mahasiswa']['nim'],
            'prodi' => $data['enrollment']['program_studi']['nama_prodi'],
            'semester' => $data['ukt_semester']['periode_pembayaran']['nama_periode'],
            'diverifikasi' => $data['approver']['nama_lengkap'] ?? null
        ];

        return view('staff-keuangan.dashboard.pengajuan-cicilan.pengajuan-cicilan-detail', compact('detailPengajuanCicilan', 'data'));
    }

    public function updateStatus(Request $request, $id)
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['error' => 'Token tidak ditemukan, harap login ulang.']);
        }

        $status = $request->input('status');

        // Validasi sederhana
        if (!in_array($status, ['approved', 'rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Status tidak valid.']);
        }

        try {
            $response = Http::withToken($token)->put(config('app.api_url') . "/api/pengajuan-cicilan/{$id}", [
                'status' => $status
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Status berhasil diperbarui.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Gagal memperbarui status.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui status.']);
        }
    }

    public function formUpdateCicilan($id)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        $data = $this->getApiData("/api/pengajuan-cicilan/{$id}", [], $token);

        if (empty($data)) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data pengajuan.']);
        }

        $detailPengajuanCicilan = [
            'id' => $id,
            'AngsuranDiajukan' => $data['jumlah_angsuran_diajukan'],
            'AngsuranDisetujui' => $data['jumlah_angsuran_disetujui'],
            'status' => ucfirst($data['status']),
            'file_path' => $data['file_path'] ?? null,
            'nama' => $data['enrollment']['mahasiswa']['nama_lengkap'],
            'nim' => $data['enrollment']['mahasiswa']['nim'],
            'prodi' => $data['enrollment']['program_studi']['nama_prodi'],
            'semester' => $data['ukt_semester']['periode_pembayaran']['nama_periode'],
            'diverifikasi' => $data['approver']['nama_lengkap'] ?? null
        ];

        return view('staff-keuangan.dashboard.pengajuan-cicilan.update_cicilan', compact('detailPengajuanCicilan', 'data'));
    }

    public function updateHasilCicilan(Request $request, $id)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        $validated = $request->validate([
            'jumlah_angsuran_disetujui' => 'required|integer|min:1',
            'catatan_approval' => 'nullable|string|max:255',
        ]);

        $payload = [
            'jumlah_angsuran_disetujui' => $validated['jumlah_angsuran_disetujui'],
            'catatan_approval' => $validated['catatan_approval'],
            'approved_by' => $userData['staff_id'],
            'approved_at' => now(),
        ];

        try {
            $response = Http::withToken($token)->put(config('app.api_url') . "/api/pengajuan-cicilan/{$id}", $payload);

            if ($response->successful()) {
                return redirect()->route('staff.pengajuan-cicilan')->with('success', 'Pengajuan cicilan berhasil diperbarui.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Gagal memperbarui pengajuan.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghubungi server.']);
        }
    }

    /**
     * Membuat tagihan baru berdasarkan pengajuan cicilan yang disetujui
     */
    public function buatTagihanBaru($pengajuanId)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        try {
            // 1. Ambil data pengajuan cicilan
            $pengajuanData = $this->getApiData("/api/pengajuan-cicilan/{$pengajuanId}", [], $token);
            
            if (empty($pengajuanData)) {
                return redirect()->back()->withErrors(['error' => 'Data pengajuan cicilan tidak ditemukan.']);
            }

            // Validasi status pengajuan harus approved
            if ($pengajuanData['status'] !== 'approved') {
                return redirect()->back()->withErrors(['error' => 'Pengajuan cicilan belum disetujui.']);
            }

            // 2. Ambil data UKT semester dari pengajuan (sudah include pembayaran)
            $uktSemesterData = $pengajuanData['ukt_semester'];
            $idUktSemester = $pengajuanData['id_ukt_semester'];
            
            if (empty($uktSemesterData)) {
                return redirect()->back()->withErrors(['error' => 'Data UKT semester tidak ditemukan.']);
            }

            // 3. Hitung nominal per cicilan dengan pembulatan yang user-friendly
            $jumlahAngsuranDisetujui = $pengajuanData['jumlah_angsuran_disetujui'];
            $totalUkt = intval($uktSemesterData['jumlah_ukt']);
            
            // Buat pembagian cicilan yang user-friendly
            $nominalCicilan = $this->hitungPembagianUserFriendly($totalUkt, $jumlahAngsuranDisetujui);

            // 4. Ambil data pembayaran UKT semester yang ada dari relasi
            $pembayaranUktData = $uktSemesterData['pembayaran'] ?? [];

            // 5. Update status pembayaran yang ada menjadi 'cancelled'
            if (!empty($pembayaranUktData)) {
                foreach ($pembayaranUktData as $pembayaran) {
                    if ($pembayaran['id_enrollment'] == $pengajuanData['id_enrollment'] && 
                        $pembayaran['id_ukt_semester'] == $idUktSemester &&
                        $pembayaran['status'] !== 'cancelled' &&
                        $pembayaran['status'] !== 'lunas') {
                        
                        $updateResult = $this->updatePembayaranStatus($pembayaran['id'], 'cancelled', $token);
                        if (!$updateResult) {
                            return redirect()->back()->withErrors(['error' => "Gagal membatalkan tagihan lama dengan ID: {$pembayaran['id']}"]);
                        }
                    }
                }
            }

            // 6. Buat tagihan baru sebanyak jumlah angsuran yang disetujui
            $tanggalMulai = Carbon::parse($uktSemesterData['periode_pembayaran']['tanggal_mulai']);
            $interval = 30; // 30 hari interval antar cicilan
            
            // Array untuk menyimpan detail cicilan (untuk pesan sukses)
            $detailCicilan = [];

            for ($i = 1; $i <= $jumlahAngsuranDisetujui; $i++) {
                $tanggalJatuhTempo = $tanggalMulai->copy()->addDays($interval * ($i - 1));
                $nominalTagihan = $nominalCicilan[$i - 1]; // Array dimulai dari 0
                
                $tagihanBaru = [
                    'id_enrollment' => $pengajuanData['id_enrollment'],
                    'id_ukt_semester' => $idUktSemester,
                    'id_jenis_pembayaran' => 2, // Assuming 2 adalah ID untuk pembayaran cicilan
                    'total_cicilan' => $jumlahAngsuranDisetujui,
                    'cicilan_ke' => $i,
                    'nominal_tagihan' => $nominalTagihan,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                    'status' => 'belum_bayar',
                    'id_pengajuan_cicilan' => $pengajuanId
                ];

                $response = Http::withToken($token)->post(config('app.api_url') . "/api/pembayaran-ukt-semester", $tagihanBaru);
                
                if (!$response->successful()) {
                    Log::error('Gagal membuat tagihan cicilan', [
                        'cicilan_ke' => $i,
                        'response_status' => $response->status(),
                        'response_body' => $response->body(),
                        'payload' => $tagihanBaru
                    ]);
                    return redirect()->back()->withErrors(['error' => "Gagal membuat tagihan cicilan ke-{$i}. " . $response->body()]);
                }
                
                // Simpan detail untuk pesan sukses
                $detailCicilan[] = "Cicilan ke-{$i}: Rp " . number_format($nominalTagihan, 0, ',', '.');
            }

            // Buat pesan sukses dengan detail cicilan
            $totalPembagian = array_sum($nominalCicilan);
            $pesanSukses = "Berhasil membuat {$jumlahAngsuranDisetujui} tagihan cicilan baru:<br>";
            $pesanSukses .= implode('<br>', $detailCicilan);
            $pesanSukses .= "<br><strong>Total: Rp " . number_format($totalPembagian, 0, ',', '.') . "</strong>";
            $pesanSukses .= "<br><em>UKT Asli: Rp " . number_format($totalUkt, 0, ',', '.') . "</em>";

            return redirect()->back()->with('success', $pesanSukses);

        } catch (\Exception $e) {
            Log::error('Error dalam buatTagihanBaru', [
                'pengajuan_id' => $pengajuanId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat membuat tagihan baru: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghitung pembagian cicilan yang user-friendly dengan nominal bulat
     */
    private function hitungPembagianUserFriendly($totalUkt, $jumlahCicilan)
    {
        // Tentukan unit pembulatan berdasarkan besaran UKT
        $unitPembulatan = $this->tentukanUnitPembulatan($totalUkt);
        
        // Hitung nominal dasar per cicilan
        $nominalDasar = intval($totalUkt / $jumlahCicilan);
        
        // Bulatkan ke unit terdekat
        $nominalBulat = $this->bulatkanKeUnit($nominalDasar, $unitPembulatan);
        
        // Inisialisasi array cicilan
        $cicilan = array_fill(0, $jumlahCicilan, $nominalBulat);
        
        // Hitung total setelah pembulatan
        $totalSetelahBulat = $nominalBulat * $jumlahCicilan;
        $selisih = $totalUkt - $totalSetelahBulat;
        
        // Distribusi selisih ke cicilan terakhir
        if ($selisih != 0) {
            $cicilan[$jumlahCicilan - 1] += $selisih;
            
            // Jika cicilan terakhir menjadi tidak bulat, distribusi ulang
            if ($cicilan[$jumlahCicilan - 1] % $unitPembulatan != 0) {
                $cicilan = $this->redistribusiSelisih($totalUkt, $jumlahCicilan, $unitPembulatan);
            }
        }
        
        return $cicilan;
    }

    /**
     * Menentukan unit pembulatan berdasarkan besaran UKT
     */
    private function tentukanUnitPembulatan($totalUkt)
    {
        if ($totalUkt >= 10000000) { // >= 10 juta
            return 500000; // Kelipatan 500rb
        } elseif ($totalUkt >= 5000000) { // >= 5 juta
            return 250000; // Kelipatan 250rb
        } elseif ($totalUkt >= 2000000) { // >= 2 juta
            return 100000; // Kelipatan 100rb
        } elseif ($totalUkt >= 1000000) { // >= 1 juta
            return 50000;  // Kelipatan 50rb
        } else {
            return 25000;  // Kelipatan 25rb
        }
    }

    /**
     * Membulatkan nominal ke unit terdekat
     */
    private function bulatkanKeUnit($nominal, $unit)
    {
        return round($nominal / $unit) * $unit;
    }

    /**
     * Redistribusi selisih dengan pendekatan yang lebih fleksibel
     */
    private function redistribusiSelisih($totalUkt, $jumlahCicilan, $unitPembulatan)
    {
        // Strategi: buat cicilan dengan nominal yang mudah dipahami
        $cicilan = [];
        $sisaUkt = $totalUkt;
        
        // Untuk cicilan pertama sampai kedua terakhir
        for ($i = 0; $i < $jumlahCicilan - 1; $i++) {
            $sisaCicilan = $jumlahCicilan - $i;
            $nominalIdeal = intval($sisaUkt / $sisaCicilan);
            $nominalBulat = $this->bulatkanKeUnit($nominalIdeal, $unitPembulatan);
            
            // Pastikan tidak melebihi sisa UKT
            if ($nominalBulat * $sisaCicilan > $sisaUkt) {
                $nominalBulat -= $unitPembulatan;
            }
            
            $cicilan[$i] = $nominalBulat;
            $sisaUkt -= $nominalBulat;
        }
        
        // Cicilan terakhir mendapat sisa
        $cicilan[$jumlahCicilan - 1] = $sisaUkt;
        
        return $cicilan;
    }

    /**
     * Update status pembayaran
     */
    private function updatePembayaranStatus($pembayaranId, $status, $token)
    {
        try {
            $response = Http::withToken($token)->put(config('app.api_url') . "/api/pembayaran-ukt-semester/{$pembayaranId}", [
                'status' => $status
            ]);
            
            if ($response->successful()) {
                Log::info("Berhasil update status pembayaran", [
                    'pembayaran_id' => $pembayaranId,
                    'status_baru' => $status
                ]);
                return true;
            } else {
                Log::error("Gagal update status pembayaran", [
                    'pembayaran_id' => $pembayaranId,
                    'status_baru' => $status,
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error update status pembayaran", [
                'pembayaran_id' => $pembayaranId,
                'status_baru' => $status,
                'error' => $e->getMessage()
            ]);
            return false;
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