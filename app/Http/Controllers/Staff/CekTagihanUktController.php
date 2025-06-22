<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class CekTagihanUktController extends Controller
{
    /**
     * Display a listing of the UKT bills.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // In a real application, you would fetch this data from your database
    //     // For now, we'll use dummy data
    //     $data = [
    //         'totalSudahLunas' => 1000,
    //         'totalBelumLunas' => 4500,
    //         'totalSemuaTagihan' => 5500,
    //     ];

    //     return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt', $data);
    // }

    public function index()
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Fetch data from API
        $dataTagihan  = $this->getApiData('/api/ukt-semester', [], $token);
        //dd($dataTagihan);

        // Kirim data ke view
        return view('staff-keuangan.dashboard.cek-tagihan-ukt.cek-tagihan-ukt', [
            'dataTagihan' => $dataTagihan,
        ]);
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

    private function getApiData($endpoint, $queryParams = [], $token)
    {
        try {
            $response = Http::withToken($token)->get(config('app.api_url') . $endpoint, $queryParams);
            return $response->successful() ? optional($response->json())['data'] ?? [] : [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
