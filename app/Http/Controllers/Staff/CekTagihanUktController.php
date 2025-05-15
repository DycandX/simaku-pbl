<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CekTagihanUktController extends Controller
{
    /**
     * Display a listing of the UKT bills.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // In a real application, you would fetch this data from your database
        // For now, we'll use dummy data
        $data = [
            'totalSudahLunas' => 1000,
            'totalBelumLunas' => 4500,
            'totalSemuaTagihan' => 5500,
        ];

        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt', $data);
    }

    /**
     * Display the specified UKT bill details.
     *
     * @param  string  $noTagihan
     * @return \Illuminate\Http\Response
     */
    public function detail($noTagihan)
    {
        // In a real application, you would fetch this specific bill data from your database
        // using the $noTagihan parameter

        // For now, we're just returning the view without specific data
        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt-detail');
    }
}