<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if session has user data
        $userData = Session::get('user_data');
        
        // If no user data in session, but we have token, try to fetch it
        if (!$userData && Session::has('token')) {
            $token = Session::get('token');
            
            try {
                $response = Http::withToken($token)->get(url('/api/user'));
                
                if ($response->successful()) {
                    $allUsers = $response->json()['data'];
                    $username = Session::get('username');
                    
                    // Find current user
                    foreach ($allUsers as $user) {
                        if ($user['username'] === $username) {
                            $userData = $user;
                            Session::put('user_data', $userData);
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                return redirect()->route('login')->withErrors(['error' => 'Sesi telah berakhir. Silakan login kembali.']);
            }
        }
        
        if (!$userData) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        return view('mahasiswa.dashboard', compact('userData'));
    }
}