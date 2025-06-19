<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class GolonganUktController extends Controller
{
    /**
     * Menampilkan halaman Golongan UKT
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Logika untuk mengambil data golongan UKT jika diperlukan
        // Untuk saat ini, kita hanya merender view tanpa data khusus

        return view('mahasiswa.golongan_ukt');
    }
}