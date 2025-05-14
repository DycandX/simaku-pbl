<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffDataMahasiswaController extends Controller
{
    public function showDataMahasiswa()
    {
        // Menampilkan view dengan nama staff-keuangan.data-mahasiswa.data-mahasiswa
        return view('staff-keuangan.data-mahasiswa.data-mahasiswa');
    }
}