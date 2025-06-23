<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class StaffDataMahasiswaController extends Controller
{
    public function showDataMahasiswa(Request $request)
    {
        // Memeriksa apakah token ada
        if (!session('token')) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Menyiapkan parameter filter dari request
        $angkatan = $request->input('angkatan', '2023');  // Default to 2023
        $jurusan = $request->input('jurusan', 'D4 - Manajemen Bisnis');  // Default to D4 - Manajemen Bisnis
        $search = $request->input('search', '');

        // Mengambil data mahasiswa dari API dengan filter
        $responseMahasiswa = Http::withToken(session('token'))
            ->get('http://simaku-pbl.test/api/mahasiswa', [
                'angkatan' => $angkatan,
                'jurusan' => $jurusan,
                'search' => $search,
            ]);

        if ($responseMahasiswa->failed()) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data mahasiswa']);
        }

        $mahasiswaData = $responseMahasiswa->json()['data'] ?? [];

        // Mengambil data enrollment mahasiswa untuk mendapatkan angkatan dan prodi
        $responseEnrollment = Http::withToken(session('token'))->get('http://simaku-pbl.test/api/enrollment-mahasiswa');
        if ($responseEnrollment->failed()) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data enrollment mahasiswa']);
        }
        $enrollmentData = $responseEnrollment->json()['data'] ?? [];

        // Mengambil data kelas untuk mendapatkan nama_kelas dan tahun_angkatan
        $responseKelas = Http::withToken(session('token'))->get('http://simaku-pbl.test/api/kelas');
        if ($responseKelas->failed()) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data kelas']);
        }
        $kelasData = $responseKelas->json()['data'] ?? [];

        // Mengambil data fakultas untuk mendapatkan nama_fakultas
        $responseFakultas = Http::withToken(session('token'))->get('http://simaku-pbl.test/api/fakultas');
        if ($responseFakultas->failed()) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengambil data fakultas']);
        }
        $fakultasData = $responseFakultas->json()['data'] ?? [];

        // Menambahkan angkatan, prodi, fakultas ke data mahasiswa berdasarkan enrollment
        foreach ($mahasiswaData as &$mahasiswa) {
            // Menambahkan informasi angkatan, prodi, dan kelas
            foreach ($enrollmentData as $enrollment) {
                if ($mahasiswa['nim'] == $enrollment['mahasiswa']['nim']) {
                    // Menambahkan angkatan dari kelas
                    $mahasiswa['angkatan'] = $enrollment['kelas']['tahun_angkatan'];

                    // Menambahkan prodi dari kelas
                    foreach ($kelasData as $kelas) {
                        if ($kelas['id'] == $enrollment['id_kelas']) {
                            $mahasiswa['prodi'] = $kelas['program_studi']['nama_prodi'];

                            // Menambahkan jurusan dari fakultas
                            foreach ($fakultasData as $fakultas) {
                                if ($fakultas['id'] == $kelas['program_studi']['id_fakultas']) {
                                    $mahasiswa['jurusan'] = $fakultas['nama_fakultas'];
                                    break;
                                }
                            }
                            break;
                        }
                    }
                }
            }
        }

        // Mengirimkan data ke view
        return view('staff-keuangan.data-mahasiswa.data-mahasiswa', [
            'mahasiswaData' => $mahasiswaData,
            'angkatan' => $angkatan,
            'jurusan' => $jurusan,
            'search' => $search
        ]);
    }
}
