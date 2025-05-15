<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffBuatTagihanUktController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.dashboard.buat-tagihan-ukt.buat-tagihan-ukt');
    }
}