<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class PembayaranUktStaffController extends Controller
{
    public function index(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        // Check if user is logged in and has valid session
        if (!$userData && !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Get filtering parameters from the request
        $semester = $request->get('semester', '');
        $status = $request->get('status', '');

        // Build query parameters
        $queryParams = [
            'semester' => $semester,
            'status' => $status
        ];

        // Fetch payment data from the API
        $pembayaranUktData = $this->getApiData('/api/pembayaran-ukt-semester', $queryParams, $token);

        // Calculate the summary values
        $totalPembayaran = 0;
        $diverifikasi = 0;
        $belumDiverifikasi = 0;
        $ditolak = 0;

        foreach ($pembayaranUktData as $data) {
            $totalPembayaran += floatval($data['total_tagihan']);
            if ($data['status'] == 'sudah lunas') {
                $diverifikasi++;
            } elseif ($data['status'] == 'belum lunas') {
                $belumDiverifikasi++;
            } else {
                $ditolak++;
            }
        }

        return view('staff-keuangan.dashboard.pembayaran-ukt.pembayaran-ukt', compact('pembayaranUktData', 'totalPembayaran', 'diverifikasi', 'belumDiverifikasi', 'ditolak', 'semester', 'status'));
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
