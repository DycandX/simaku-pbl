<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class dataBandingUktController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.data-mahasiswa.data-banding-ukt');
    }
}