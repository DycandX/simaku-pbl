<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DaftarUlangController extends Controller
{
    // public function index()
    // {
    //     $userData = Session::get('user_data');
    //     $token = Session::get('token');

    //     // Cek session user
    //     if (!$userData && $token) {
    //         try {
    //             $response = Http::withToken($token)->get(config('app.api_url') . '/api/user');
    //             if ($response->successful()) {
    //                 $allUsers = optional($response->json())['data'] ?? [];
    //                 $username = Session::get('username');

    //                 foreach ($allUsers as $user) {
    //                     if ($user['username'] === $username) {
    //                         $userData = $user;
    //                         Session::put('user_data', $userData);
    //                         break;
    //                     }
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             return redirect()->route('login')->withErrors(['error' => 'Sesi telah berakhir. Silakan login kembali.']);
    //         }
    //     }

    //     if (!$userData) {
    //         return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
    //     }

    //     // Ambil NIM dari session
    //     $nim = Session::get('username');

    //     // Ambil data UKT Semester
    //     $uktSemesters = $this->getApiData("/api/ukt-semester?nim=" . urlencode($nim), $token);
    //     dd($uktSemesters);
    //     // Olah data transaksi pembayaran UKT
    //     $dataTransaksi = collect($uktSemesters)->flatMap(function ($ukt, $index) {
    //         //  dd($ukt);
    //         $periode = $ukt['periode_pembayaran'] ?? [];
    //         $pembayaranList = $ukt['pembayaran'] ?? [];
    //         // Kalau tidak ada pembayaran, kembalikan array kosong (jangan tampilkan)
    //         if (empty($pembayaranList)) {
    //             return [];
    //         }

    //         return collect($pembayaranList)->flatMap(function ($item, $i) use ($periode) {
    //             $statusPayment = $item['status'] === 'verified' ? 'Sudah Dibayar' : 'Belum Dibayar';

    //             return collect($item['detail_pembayaran'] ?? [])->map(function ($detail, $j) use ($item, $periode, $statusPayment) {
    //                 return [
    //                     'no' => $j + 1,
    //                     'no_tagihan' => 'INV' . str_pad($item['id'], 5, '0', STR_PAD_LEFT),
    //                     'tanggal_terbit' => isset($periode['tanggal_mulai']) ? Carbon::parse($periode['tanggal_mulai'])->translatedFormat('d M Y') : '-',
    //                     'jatuh_tempo' => isset($item['tanggal_jatuh_tempo']) ? Carbon::parse($item['tanggal_jatuh_tempo'])->translatedFormat('d M Y') : '-',
    //                     'semester' => $periode['nama_periode'] ?? '-',
    //                     'total' => 'Rp ' . number_format((float)($item['nominal_tagihan'] ?? 0), 0, ',', '.') . ',-',
    //                     'bank' => $detail['metode_pembayaran'] ?? '-',
    //                     'status_tagihan' => $item['status'] ?? '-',
    //                     'status_payment' => $statusPayment,
    //                     'keterangan' => $detail['catatan'] ?? '-',
    //                     'bukti' => $detail['bukti_pembayaran_path'] ?? '-',
    //                 ];
    //             });
    //         });
    //     });



    //     // Ambil data daftar ulang (enrollment)
    //     $enrollments = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);

    //     $dataDaftarUlang = collect($enrollments)->map(function ($item, $index) {
    //         $kelas = $item['kelas']['nama_kelas'] ?? '-';
    //         $semester = $item['tahun_akademik']['tahun_akademik'] ?? '-';
    //         $semester .= isset($item['tahun_akademik']['semester']) ? ' - ' . $item['tahun_akademik']['semester'] : '';

    //         $tanggalDaftarUlang = isset($item['tahun_akademik']['tanggal_mulai']) ?
    //             Carbon::parse($item['tahun_akademik']['tanggal_mulai'])->translatedFormat('d M Y') : '-';

    //         return [
    //             'no' => $index + 1,
    //             'kelas' => $item['tingkat']['nama_tingkat'] . ' ' . $kelas,
    //             'semester' => $semester,
    //             'daftar_ulang' => 'Sudah',
    //             'tanggal_daftar_ulang' => $tanggalDaftarUlang,
    //             'status' => $item['tahun_akademik']['status'] ?? '-',
    //             'urutan_semester' => $index + 1,
    //         ];
    //     });
    //     //dd($dataTransaksi);
    //     return view('mahasiswa.dashboard.daful-ukt.daftar_ulang', compact('dataTransaksi', 'userData', 'dataDaftarUlang'));
    // }

    // private function getApiData($endpoint, $token)
    // {
    //     try {
    //         $response = Http::withToken($token)->get(config('app.api_url') . $endpoint);
    //         return $response->successful() ? optional($response->json())['data'] ?? [] : [];
    //     } catch (\Exception $e) {
    //         return [];
    //     }
    // }
    public function index()
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Cek session user
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

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Ambil NIM dari session
        $nim = Session::get('username');

        // Ambil data UKT Semester (sudah termasuk enrollment)
        $uktSemesters = $this->getApiData("/api/ukt-semester?nim=" . urlencode($nim), $token);

        // Olah data transaksi pembayaran UKT
        $dataTransaksi = collect($uktSemesters)->flatMap(function ($ukt, $index) {
            $periode = $ukt['periode_pembayaran'] ?? [];
            $pembayaranList = $ukt['pembayaran'] ?? [];

            if (empty($pembayaranList)) {
                return [];
            }

            return collect($pembayaranList)->flatMap(function ($item, $i) use ($periode) {
                return collect($item['detail_pembayaran'] ?? [])->map(function ($detail, $j) use ($item, $periode) {
                    return [
                        'no' => $j + 1,
                        'no_tagihan' => 'INV' . str_pad($item['id'], 5, '0', STR_PAD_LEFT),
                        'tanggal_terbit' => isset($periode['tanggal_mulai']) ? Carbon::parse($periode['tanggal_mulai'])->translatedFormat('d M Y') : '-',
                        'jatuh_tempo' => isset($item['tanggal_jatuh_tempo']) ? Carbon::parse($item['tanggal_jatuh_tempo'])->translatedFormat('d M Y') : '-',
                        'semester' => $periode['nama_periode'] ?? '-',
                        'total' => 'Rp ' . number_format((float)($item['nominal_tagihan'] ?? 0), 0, ',', '.') . ',-',
                        'bank' => $detail['metode_pembayaran'] ?? '-',
                        'status_tagihan' => $item['status'] ?? '-',
                        'keterangan' => $detail['catatan'] ?? '-',
                        'bukti' => $detail['bukti_pembayaran_path'] ?? '-',
                    ];
                });
            });
        });

        // Data daftar ulang (enrollment) dari dalam $uktSemesters
        // $dataDaftarUlang = collect($uktSemesters)->map(function ($ukt, $index) {
        //     $enrollment = $ukt['enrollment'] ?? null;

        //     if (!$enrollment) {
        //         return null;
        //     }

        //     $kelas = $enrollment['kelas']['nama_kelas'] ?? '-';
        //     $semester = $enrollment['tahun_akademik']['tahun_akademik'] ?? '-';
        //     $semester .= isset($enrollment['tahun_akademik']['semester']) ? ' - ' . $enrollment['tahun_akademik']['semester'] : '';

        //     $tanggalDaftarUlang = isset($enrollment['tahun_akademik']['tanggal_mulai']) ?
        //         Carbon::parse($enrollment['tahun_akademik']['tanggal_mulai'])->translatedFormat('d M Y') : '-';

        //     return [
        //         'no' => $index + 1,
        //         'kelas' => ($enrollment['tingkat']['nama_tingkat'] ?? '-') . ' ' . $kelas,
        //         'semester' => $semester,
        //         'daftar_ulang' => 'Sudah',
        //         'tanggal_daftar_ulang' => $tanggalDaftarUlang,
        //         'status' => $enrollment['tahun_akademik']['status'] ?? '-',
        //         'urutan_semester' => $index + 1,
        //     ];
        // })->filter(); // Hilangkan null jika ada yang tidak punya enrollment
        $dataDaftarUlang = collect($uktSemesters)->map(function ($ukt, $index) {
        $enrollment = $ukt['enrollment'] ?? null;
        $pembayaran = $ukt['pembayaran'] ?? [];

        // Jika tidak ada pembayaran, abaikan walaupun ada enrollment
        if (!$enrollment || empty($pembayaran)) {
            return null;
        }

        $kelas = $enrollment['kelas']['nama_kelas'] ?? '-';
        $semester = $enrollment['tahun_akademik']['tahun_akademik'] ?? '-';
        $semester .= isset($enrollment['tahun_akademik']['semester']) ? ' - ' . $enrollment['tahun_akademik']['semester'] : '';

        $tanggalDaftarUlang = isset($enrollment['tahun_akademik']['tanggal_mulai']) ?
            Carbon::parse($enrollment['tahun_akademik']['tanggal_mulai'])->translatedFormat('d M Y') : '-';

        return [
            'no' => $index + 1,
            'kelas' => $kelas,
            'semester' => $semester,
            'daftar_ulang' => 'Sudah',
            'tanggal_daftar_ulang' => $tanggalDaftarUlang,
            'status' => $enrollment['tahun_akademik']['status'] ?? '-',
            'urutan_semester' => $index + 1,
        ];
    })->filter(); // Hilangkan null jika ada yang tidak punya pembayaran


        //dd($dataDaftarUlang);
        //dd($dataTransaksi);
        //dd($uktSemesters);
        return view('mahasiswa.dashboard.daful-ukt.daftar_ulang', compact('dataTransaksi', 'userData', 'dataDaftarUlang'));
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
