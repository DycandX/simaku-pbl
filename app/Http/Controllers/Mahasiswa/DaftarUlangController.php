<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DaftarUlangController extends Controller
{
    public function index()
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Validasi session user
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
                return redirect()->route('login')->withErrors(['error' => 'Sesi habis. Silakan login ulang.']);
            }
        }

        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        $nim = Session::get('username');
        $response = $this->getApiData("/api/detail-pembayaran?nim=" . urlencode($nim), $token);
        
        $dataTransaksi = collect($response)->map(function ($item, $index) {
            $pembayaran = $item['pembayaran_ukt_semester'] ?? [];
            $uktSemester = $pembayaran['ukt_semester'] ?? [];
            $periode = $uktSemester['periode_pembayaran'] ?? [];
            //dd($periode);
        
            return [
                'no' => $index + 1,
                'no_tagihan' => 'INV' . str_pad($item['id'], 5, '0', STR_PAD_LEFT),
                'tanggal_terbit' => isset($periode['tanggal_mulai']) ? Carbon::parse($periode['tanggal_mulai'])->translatedFormat('d M Y') : '-',
                'jatuh_tempo' => isset($periode['tanggal_selesai']) ? Carbon::parse($periode['tanggal_selesai'])->translatedFormat('d M Y') : '-',
                'semester' => $periode['nama_periode'] ?? '-', // Lebih akurat
                'total' => 'Rp ' . number_format((float)($pembayaran['nominal_tagihan'] ?? 0), 0, ',', '.') . ',-',
                'bank' => $item['metode_pembayaran'] ?? '-',
                'status_tagihan' => $pembayaran['status'] ?? '-',
                'status_payment' => $item['status'] === 'verified' ? 'Sudah Dibayar' : 'Belum Dibayar',
                'keterangan' => $item['catatan'] ?? '-',
                'bukti' => $item['bukti_pembayaran_path'] ?? null,
            ];
        });
        
            // Ambil data daftar ulang (enrollment) mahasiswa
        $enrollments = $this->getApiData("/api/enrollment-mahasiswa?nim=" . urlencode($nim), $token);

        $dataDaftarUlang = collect($enrollments)->map(function ($item, $index) {
            $kelas = $item['kelas']['nama_kelas'] ?? '-';
            $semester = $item['tahun_akademik']['tahun_akademik'] ?? '-';
            $semester .= isset($item['tahun_akademik']['semester']) ? ' - ' . $item['tahun_akademik']['semester'] : '';
            
        
            $tanggalDaftarUlang = isset($item['tahun_akademik']['tanggal_mulai']) ?
                Carbon::parse($item['tahun_akademik']['tanggal_mulai'])->translatedFormat('d M Y') : '-';

            
            return [
                'no' => $index + 1,
                'kelas' => '' . $item['tingkat']['nama_tingkat'] . $kelas,
                'semester' => $semester,
                'daftar_ulang' => 'Sudah',
                'tanggal_daftar_ulang' => $tanggalDaftarUlang,
                'status' => $item['tahun_akademik']['status'] ?? '-',
                'urutan_semester' => $index + 1,
            ];
        });
        //dd($dataDaftarUlang);

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
