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

        $dataTagihan = [];

        foreach ($uktSemester as $ukt) {
            $idUkt = $ukt['id'];

            // Ambil semua pembayaran berdasarkan id_ukt_semester
            $pembayaranUkt = array_filter($pembayaran, fn($p) => $p['id_ukt_semester'] == $idUkt);

            if (empty($pembayaranUkt)) continue;

            // Hilangkan pembayaran yang statusnya 'cancelled'
            $pembayaranUktValid = array_filter($pembayaranUkt, fn($p) => strtolower($p['status']) !== 'cancelled');

            // Jika setelah difilter tidak ada data tersisa, skip
            if (empty($pembayaranUktValid)) continue;

            // Total tagihan dihitung dari semua pembayaran valid
            $totalTagihan = array_sum(array_column($pembayaranUktValid, 'nominal_tagihan'));

            // Hitung total yang sudah dibayar (status == 'terbayar')
            $totalTerbayar = array_sum(array_map(function ($p) {
                return strtolower($p['status']) === 'terbayar' ? $p['nominal_tagihan'] : 0;
            }, $pembayaranUktValid));

            // Ambil status-status untuk pengecekan keseluruhan status
            $statusList = array_map(fn($p) => strtolower($p['status']), $pembayaranUktValid);

            if (in_array('belum_bayar', $statusList) || in_array('over', $statusList)) {
                $statusRaw = 'belum_bayar';
                $displayStatus = 'Belum Lunas';
            } else {
                $statusRaw = 'terbayar';
                $displayStatus = 'Sudah Lunas';
            }

            $jenisList = array_values(array_map(fn($p) => $p['jenis_pembayaran']['id'] ?? null, $pembayaranUktValid));
            $uniqueJenis = array_unique(array_filter($jenisList)); // filter null jika ada

            if (count($uniqueJenis) === 1 && reset($uniqueJenis) == 1) {
                $jenis = 'Kontan';
            } else {
                $jenis = 'Cicilan';
            }

            // Ambil salah satu ID pembayaran untuk tombol lihat
            $firstPembayaran = reset($pembayaranUktValid);

            $dataTagihan[] = [
                'id_pembayaran'    => $firstPembayaran['id'],
                'id_ukt_semester'  => $idUkt,
                'periode'          => $ukt['periode_pembayaran']['nama_periode'] ?? '-',
                'nominal_tagihan'  => $totalTagihan,
                'total_terbayar'   => $totalTerbayar,
                'status_raw'       => $statusRaw,
                'status'           => $displayStatus,
                'jenis'            => $jenis,
            ];
        }

        //dd($dataTagihan);
        return view('mahasiswa.dashboard.tagihan-ukt.lihat_tagihan_ukt', compact('userData', 'uktSemester', 'pembayaran', 'detailPembayaran', 'dataTagihan'));
    }

    public function show($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');
        $nim = Session::get('username');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // 1. Ambil data UKT Semester
        $uktSemester = $this->getApiData("/api/ukt-semester/$id", $token);

        if (empty($uktSemester)) {
            return redirect()->back()->withErrors(['error' => 'Data UKT Semester tidak ditemukan.']);
        }
        // Debug data
        //dd($uktSemester);
        // dd($allDetails);
        // dd($pembayaranUkt);
        // dd($pembayaranIds);

        return view('mahasiswa.dashboard.tagihan-ukt.detail_tagihan_ukt', compact(
            'userData',
            'uktSemester'
        ));
    }

    public function pengajuan_cicilan($id)
    {
        // dd($id);
        $token = Session::get('token');
        $userData = Session::get('user_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil data UKT Semester berdasarkan ID
        $uktSemester = $this->getApiData("/api/ukt-semester/$id", $token);

        if (empty($uktSemester)) {
            return redirect()->back()->withErrors(['error' => 'Data UKT Semester tidak ditemukan.']);
        }

        //dd($uktSemester);
        return view('mahasiswa.dashboard.tagihan-ukt.pengajuan_cicilan', [
            'uktSemester' => $uktSemester,
            'userData' => $userData,
        ]);
    }

    
    public function pengajuan_cicilan_store(Request $request)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validasi input
        $validated = $request->validate([
            'id_enrollment' => 'required|integer',
            'id_ukt_semester' => 'required|integer',
            'jumlah_angsuran_diajukan' => 'required|integer|min:2|max:4',
            'alasan_pengajuan' => 'required|string',
            'file_path' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            $file = $request->file('file_path');
            $multipart = [];

            if ($file->isValid()) {
                $multipart[] = [
                    'name'     => 'file_path',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ];
            }

            // Tambah field lainnya
            $fields = [
                'id_enrollment'            => $validated['id_enrollment'],
                'id_ukt_semester'          => $validated['id_ukt_semester'],
                'jumlah_angsuran_diajukan' => $validated['jumlah_angsuran_diajukan'],
                'alasan_pengajuan'         => $validated['alasan_pengajuan'],
                'status'                   => 'pending',
            ];

            foreach ($fields as $key => $value) {
                $multipart[] = [
                    'name'     => $key,
                    'contents' => $value,
                ];
            }

            $response = Http::withToken($token)
                ->asMultipart()
                ->post(config('app.api_url') . '/api/pengajuan-cicilan', $multipart);

            if ($response->failed()) {
                dd([
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'json'   => $response->json(),
                ]);
            }

            return redirect()->route('mahasiswa-dashboard')
                ->with('success', 'Pengajuan cicilan berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengirim data: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    // public function upload_bukti_pembayaran($id)
    // {
    //     $token = Session::get('token');
    //     $userData = Session::get('user_data');
    //     $nim = Session::get('username');

    //     if (!$userData) {
    //         return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
    //     }

    //     // Ambil data detail pembayaran berdasarkan ID
    //     $detailPembayaran = $this->getApiData("/api/detail-pembayaran/$id", $token);
    //     //dd($detailPembayaran);
    //     // Ambil data enrollment mahasiswa berdasarkan NIM
    //     $enrollmentMahasiswa = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);

    //     if (empty($detailPembayaran)) {
    //         return redirect()->back()->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
    //     }

    //     return view(
    //         'mahasiswa.dashboard.tagihan-ukt.upload_bukti',
    //         compact('detailPembayaran', 'enrollmentMahasiswa', 'userData')
    //     );
    // }
    public function upload_bukti_pembayaran($id_ukt_semester)
    {
        //dd($id_ukt_semester);
        $token = Session::get('token');
        $userData = Session::get('user_data');
        $nim = Session::get('username');
        

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil data UKT Semester berdasarkan ID
        $uktSemester = $this->getApiData("/api/ukt-semester/$id_ukt_semester", $token);

        // Ambil data enrollment mahasiswa berdasarkan NIM
        $enrollmentMahasiswa = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);

        if (empty($uktSemester)) {
            return redirect()->back()->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
        }

        $pembayaranList = $uktSemester['pembayaran']; // Ambil daftar pembayaran cicilan
        //dd($pembayaranList);
        //dd($enrollmentMahasiswa);
        return view('mahasiswa.dashboard.tagihan-ukt.upload_bukti', [
            'uktSemester' => $uktSemester,
            'pembayaranList' => $pembayaranList,
            'enrollmentMahasiswa' => $enrollmentMahasiswa,
            'userData' => $userData
        ]);
    }


    public function bukti_pembayaran_store(Request $request)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validasi input dari form
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'bank_pengirim' => 'required|string|max:255',
            'jumlah_dibayar' => 'required|numeric|min:1000',
            'bukti_pembayaran_path' => 'required|file|mimes:jpeg,jpg,png|max:1024',
        ]);

        // try {
        //     $storedPath = null;

        //     if ($request->hasFile('bukti_pembayaran_path')) {
        //         $storedPath = $request->file('bukti_pembayaran_path')->store('bukti-pembayaran', 'public');
        //     }

        //     // Kirim path sebagai string biasa
        //     $response = Http::withToken($token)
        //         ->post(config('app.api_url') . '/api/detail-pembayaran', [
        //             'tanggal_pembayaran' => $validated['tanggal_transfer'],
        //             'metode_pembayaran' => $validated['bank_pengirim'],
        //             'nominal' => $validated['jumlah_dibayar'],
        //             'bukti_pembayaran_path' => $storedPath, // Ini string, bukan file
        //             'id_pembayaran_ukt_semester' => $request->input('id_pembayaran'),
        //         ]);
            try {
                $storedPath = null;

                if ($request->hasFile('bukti_pembayaran_path')) {
                    $originalName = $request->file('bukti_pembayaran_path')->getClientOriginalName();
                    $storedPath = $request->file('bukti_pembayaran_path')->storeAs('bukti-pembayaran', $originalName, 'public');
                }

                $response = Http::withToken($token)
                    ->asMultipart()
                    ->post(config('app.api_url') . '/api/detail-pembayaran', [
                        [
                            'name'     => 'tanggal_pembayaran',
                            'contents' => $validated['tanggal_transfer'],
                        ],
                        [
                            'name'     => 'metode_pembayaran',
                            'contents' => $validated['bank_pengirim'],
                        ],
                        [
                            'name'     => 'nominal',
                            'contents' => $validated['jumlah_dibayar'],
                        ],
                        [
                            'name'     => 'bukti_pembayaran_path',
                            'contents' => $storedPath, // kirim string path, bukan file fisik
                        ],
                        [
                            'name'     => 'id_pembayaran_ukt_semester',
                            'contents' => $request->input('id_pembayaran'),
                        ],
                    ]);
                    
            if ($response->failed()) {
                dd([
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'json'   => $response->json(),
                ]);
            }

            if ($response->successful()) {
                return redirect()->route('mahasiswa-dashboard')->with('success', 'Bukti pembayaran berhasil dikirim.');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Gagal mengirim data.';
                return back()->withErrors(['error' => $errorMessage])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
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
