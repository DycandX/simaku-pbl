<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffDetailBuatTagihanUktController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.dashboard.buat-tagihan-ukt.buat-tagihan-ukt');
    }
    
    public function create() 
    {
        return view('staff-keuangan.dashboard.buat-tagihan-ukt.detail-buat-tagihan-ukt');
    }
}