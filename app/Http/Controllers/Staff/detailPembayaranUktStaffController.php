<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class detailPembayaranUktStaffController extends Controller
{
    public function detail()
    {
        // In a real application, you would fetch this specific bill data from your database
        // using the $noTagihan parameter

        // For now, we're just returning the view without specific data
        return view('staff-keuangan.dashboard.pembayaran-ukt.detail-pembayaran-ukt');
    }
}