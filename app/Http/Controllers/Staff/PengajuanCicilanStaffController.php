<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

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
            'diverifikasi' => collect($allPengajuanCicilan)->where('status', 'Diverifikasi')->count(),
            'belum_diverifikasi' => collect($allPengajuanCicilan)->where('status', 'Belum Diverifikasi')->count(),
            'ditolak' => collect($allPengajuanCicilan)->where('status', 'Ditolak')->count(),
        ];

        // Data untuk dropdown filter
        $semesterOptions = collect($allPengajuanCicilan)->pluck('semester')->unique()->values();
        $jurusanOptions = collect($allPengajuanCicilan)->pluck('jurusan')->unique()->values();
        $prodiOptions = collect($allPengajuanCicilan)->pluck('prodi')->unique()->values();
        //dd($pengajuanData);
        // dd($allPengajuanCicilan);
        //dd($periodePembayaran);
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

        //dd($data);
        //dd($detailPengajuanCicilan);
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
        //dd($data);
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
        //dd($detailPengajuanCicilan);
        //dd($data);
        return view('staff-keuangan.dashboard.pengajuan-cicilan.update_cicilan', compact('detailPengajuanCicilan', 'data'));
    }

    public function updateHasilCicilan(Request $request, $id)
    {
        //dd($request->method(), $id, $request->all());
        $userData = Session::get('user_data');
        $token = Session::get('token');
        //dd(Session::get('user_data'));

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
                // dd($response->status(), $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghubungi server.']);
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
