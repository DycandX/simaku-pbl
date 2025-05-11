<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class staffBeasiswaController extends Controller
{
    public function index()
    {
        return view('staff.beasiswa');
    }
}