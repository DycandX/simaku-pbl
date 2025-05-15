<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffDetailDataMahasiswaController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.data-mahasiswa.detail-data-mahasiswa');
    }

    public function detail()
    {
        // In a real application, you would fetch this specific bill data from your database
        // using the $noTagihan parameter

        // For now, we're just returning the view without specific data
        return view('staff-keuangan.data-mahasiswa.detail-data-mahasiswa');
    }
}