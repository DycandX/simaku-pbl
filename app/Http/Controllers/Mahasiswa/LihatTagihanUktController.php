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
        $enrollmentMahasiswa = $this->getApiData("/api/enrollment-mahasiswa?nim=". urlencode($nim), $token);
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
        return view('mahasiswa.dashboard.tagihan-ukt.pengajuan_cicilan', 
        compact('pembayaranUkt', 'detailPembayaran', 'userData', 'enrollmentMahasiswa', 'namaProdi', 'responseFakultas'));
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