<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $nim = Session::get('username'); // Ambil NIM dari session

        $response = Http::withToken($token)->get(config('app.api_url') . "/api/mahasiswa?nim=" . urlencode($nim));
        $data = optional($response->json())['data'][0] ?? null;

        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Data mahasiswa tidak ditemukan']);
        }

        $mahasiswa = [
            'nama' => $data['nama_lengkap'] ?? '-',
            'nim' => $data['nim'] ?? '-',
            'alamat' => $data['alamat'] ?? '-',
            'telepon' => $data['no_telepon'] ?? '-',
            'foto' => $data['foto_path']
                ? asset('assets/' . $data['foto_path'])
                : asset('assets/Profile.jpeg'),
        ];
       //dd($mahasiswa);

        return view('mahasiswa.profile.profile', compact('mahasiswa'));
    }
}
