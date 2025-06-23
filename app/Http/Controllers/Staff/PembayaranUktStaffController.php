<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembayaranUktStaffController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.dashboard.pembayaran-ukt.pembayaran-ukt');
    }
}