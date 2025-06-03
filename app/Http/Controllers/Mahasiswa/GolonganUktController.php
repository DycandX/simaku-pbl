<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GolonganUktController extends Controller
{
    public function index()
    {

        return view('mahasiswa.dashboard.daful-ukt.golongan_ukt');
    }
}
