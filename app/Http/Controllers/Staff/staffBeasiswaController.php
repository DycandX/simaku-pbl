<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffBeasiswaController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.beasiswa.staff-beasiswa');
    }
}
