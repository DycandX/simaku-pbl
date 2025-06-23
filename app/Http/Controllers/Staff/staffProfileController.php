<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class staffProfileController extends Controller
{
    public function index()
    {
        return view('staff-keuangan.profile.staff-profile');
    }
}