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
        // dd($detailPembayaran);
        // dd($pembayaranUkt);
        // dd($pembayaranIds);
        
        return view('mahasiswa.dashboard.tagihan-ukt.detail_tagihan_ukt', compact(
            'userData',
            'uktSemester'
        ));
    }


    // public function show($id)
    // {
    //     $token = Session::get('token');
    //     $userData = Session::get('user_data');
    //     $nim = Session::get('username');

    //     if (!$userData) {
    //         return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
    //     }

    //     // 1. Ambil data UKT Semester
    //     $uktSemester = $this->getApiData("/api/ukt-semester/$id", $token);

    //     if (empty($uktSemester)) {
    //         return redirect()->back()->withErrors(['error' => 'Data UKT Semester tidak ditemukan.']);
    //     }
    //     //dd($uktSemester);
    //     // 2. Ambil semua pembayaran UKT berdasarkan id_ukt_semester
    //     $pembayaranUkt = $uktSemester['pembayaran'] ?? [];
    //     // 3. Ambil semua detail pembayaran yang terkait dengan id pembayaran UKT
    //     $detailPembayaran = [];
    //     $pembayaranIds = []; // untuk menyimpan semua ID pembayaran

    //     // foreach ($pembayaranUkt as $pembayaran) {
    //     //     $pembayaranId = $pembayaran['id'];
    //     //     $pembayaranIds[] = $pembayaranId; // simpan semua id

    //     //     $detail = $this->getApiData("/api/detail-pembayaran/$pembayaranId", $token); // pastikan endpoint ini tersedia
            
    //     //     if (!empty($detail)) {
    //     //         if (isset($detail[0])) {
    //     //             foreach ($detail as $d) {
    //     //                 $detailPembayaran[] = $d;
    //     //             }
    //     //         } else {
    //     //             $detailPembayaran[] = $detail;
    //     //         }
    //     //     }
    //     // }
    //     foreach ($pembayaranUkt as $pembayaran) {
    //         $pembayaranId = $pembayaran['id'];
    //         $pembayaranIds[] = $pembayaranId;

    //         $detail = $this->getApiData("/api/detail-pembayaran/$pembayaranId", $token);

    //         if (!empty($detail)) {
    //             $details = isset($detail[0]) ? $detail : [$detail];

    //             foreach ($details as $d) {
    //                 // Cek apakah data detail ini milik ukt_semester yang diinginkan
    //                 if (
    //                     isset($d['pembayaran_ukt_semester']['ukt_semester']['id']) &&
    //                     $d['pembayaran_ukt_semester']['ukt_semester']['id'] == $uktSemester['id']
    //                 ) {
    //                     $detailPembayaran[] = $d;
    //                 }
    //             }
    //         }
    //     }

    //     //dd($pembayaranIds); // sekarang akan menampilkan semua id pembayaran
    //     dd($detailPembayaran);
    //     //dd($uktSemester);
    //     return view('mahasiswa.dashboard.tagihan-ukt.detail_tagihan_ukt', compact('pembayaranUkt', 'detailPembayaran', 'userData', 'uktSemester'));
    // }


    public function pengajuan_cicilan($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');
        // Ambil NIM dari user yang login
        $nim =  Session::get('username');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil data detail tagihan berdasarkan ID
        $pembayaranUkt = $this->getApiData("/api/pembayaran-ukt-semester/$id", $token);
        //dd($pembayaranUkt);
        $detailPembayaran = $this->getApiData("/api/detail-pembayaran/$id", $token);
        //dd($detailPembayaran);
        $enrollmentMahasiswa = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);
        //dd($enrollmentMahasiswa);
        $programStudi = $this->getApiData("/api/program-studi", $token);

        if (empty($pembayaranUkt) && empty($detailPembayaran)) {
            return redirect()->back()->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
        }

        $idProdi = $enrollmentMahasiswa[0]['kelas']['id_prodi'] ?? null;
        $namaProdi = null;
        $idFakultas = null;

        if ($idProdi && !empty($programStudi)) {
            foreach ($programStudi as $prodi) {
                if ($prodi['id'] == $idProdi) {
                    $namaProdi = $prodi['nama_prodi'];
                    $idFakultas = $prodi['id_fakultas'];
                    break;
                }
            }
        }
        //dd($idFakultas);
        //dd($namaProdi);

        $responseFakultas = $this->getApiData("/api/fakultas/{$idFakultas}", $token);
        $fakultas = $responseFakultas['data'] ?? null;
        $namaFakultas = $fakultas['nama_fakultas'] ?? null;

        //dd($responseFakultas);
        return view(
            'mahasiswa.dashboard.tagihan-ukt.pengajuan_cicilan',
            compact('pembayaranUkt', 'detailPembayaran', 'userData', 'enrollmentMahasiswa', 'namaProdi', 'responseFakultas')
        );
    }

    public function pengajuan_cicilan_store(Request $request)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validasi input dari form
        $validated = $request->validate([
            'angsuran' => 'required|integer|min:1',
            'file_path' => 'required|file|mimes:pdf|max:2048',
            'id_pembayaran' => 'required|integer',
        ]);

        try {
            $file = $request->file('file_path');

            // Kirim file dan data lain sebagai multipart
            $response = Http::withToken($token)
                ->asMultipart() // Ini penting
                ->post(config('app.api_url') . '/api/pengajuan-cicilan', [
                    [
                        'name'     => 'file_path',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'nama_lengkap',
                        'contents' => $request->input('nama_lengkap'),
                    ],
                    [
                        'name' => 'nim',
                        'contents' => $request->input('nim'),
                    ],
                    [
                        'name' => 'fakultas',
                        'contents' => $request->input('fakultas'),
                    ],
                    [
                        'name' => 'program_studi',
                        'contents' => $request->input('program_studi'),
                    ],
                    [
                        'name' => 'tagihan',
                        'contents' => $request->input('tagihan'),
                    ],
                    [
                        'name' => 'angsuran',
                        'contents' => $validated['angsuran'],
                    ],
                    [
                        'name' => 'id_pembayaran',
                        'contents' => $validated['id_pembayaran'],
                    ],
                    [
                        'name' => 'status',
                        'contents' => false,
                    ],
                ]);

            //dd($response->json());
            if ($response->successful()) {
                return redirect()->route('mahasiswa-dashboard')->with('success', 'Pengajuan cicilan berhasil dikirim.');
            } else {
                // Tampilkan respon dari API agar tahu apa masalahnya
                $errorMessage = $response->json()['message'] ?? 'Gagal mengirim data (tanpa pesan error dari API).';
                return back()->withErrors(['error' => $errorMessage])->withInput();
            }
        } catch (\Exception $e) {
            // Untuk menampilkan pesan error spesifik saat terjadi exception
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengirim data: ' . $e->getMessage()])->withInput();
        }
    }

    public function upload_bukti_pembayaran($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user_data');
        $nim = Session::get('username');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil data detail pembayaran berdasarkan ID
        $detailPembayaran = $this->getApiData("/api/detail-pembayaran/$id", $token);
        //dd($detailPembayaran);
        // Ambil data enrollment mahasiswa berdasarkan NIM
        $enrollmentMahasiswa = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);

        if (empty($detailPembayaran)) {
            return redirect()->back()->withErrors(['error' => 'Data tagihan tidak ditemukan.']);
        }

        return view(
            'mahasiswa.dashboard.tagihan-ukt.upload_bukti',
            compact('detailPembayaran', 'enrollmentMahasiswa', 'userData')
        );
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

        try {
            $file = $request->file('file_path');

            $response = Http::withToken($token)
                ->asMultipart()
                ->post(config('app.api_url') . '/api/detail-pembayaran', [
                    [
                        'name'     => 'tanggal_transfer',
                        'contents' => $validated['tanggal_pembayaran'],
                    ],
                    [
                        'name'     => 'bank_pengirim',
                        'contents' => $validated['metode_pembayaran'],
                    ],
                    [
                        'name'     => 'jumlah_dibayar',
                        'contents' => $validated['nominal'],
                    ],
                    [
                        'name'     => 'bukti_pembayaran_path',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name'     => 'tagihan',
                        'contents' => $request->input('tagihan'), // Nama Periode (readonly)
                    ],
                ]);

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
