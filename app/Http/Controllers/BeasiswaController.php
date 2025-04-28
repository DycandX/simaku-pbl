<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.beasiswa');
    }
}