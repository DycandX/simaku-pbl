<?php

namespace App\Http\Controllers\Staff;

use App\Models\Enrollment;
use App\Models\PeriodePembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class staffBuatTagihanUktController extends Controller
{
    public function index()
    {
        // Get user data and token from session
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in and has a valid session
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Menampilkan halaman daftar tagihan UKT
        return view('staff-keuangan.dashboard.buat-tagihan-ukt.buat-tagihan-ukt');
    }

    public function create()
    {
        // Get user data and token from session
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in and has a valid session
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        return view('staff-keuangan.dashboard.buat-tagihan-ukt.create-buat-tagihan-ukt', compact('mahasiswa', 'periodePembayaran'));
    }
}
