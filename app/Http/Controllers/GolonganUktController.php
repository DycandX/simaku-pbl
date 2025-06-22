<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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