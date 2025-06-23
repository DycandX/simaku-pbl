<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class staffDetailBuatTagihanUktController extends Controller
{
    public function index()
    {
        $userData = Session::get('user_data');
        //dd($userData);
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
        $dataTagihan = $this->getApiData('/api/ukt-semester', [], $token);
        $periodePembayaran = $this->getApiData('/api/periode-pembayaran', [], $token);
        $mahasiswaData = $this->getApiData('/api/enrollment-mahasiswa', [], $token);  // Ensure this is returned
        
        //dd($mahasiswaData);

        // Kirim data ke view
        return view('staff-keuangan.dashboard.buat-tagihan-ukt.detail-buat-tagihan-ukt', [
            'dataTagihan' => $dataTagihan,
            'periodePembayaran' => $periodePembayaran,
            'mahasiswaData' => $mahasiswaData
        ]);
    }

    /**
     * Method untuk mengambil data dari API
     */
    private function getApiData($endpoint, $params = [], $token = null)
    {
        try {
            // Setup headers
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
            
            // Add authorization header if token exists
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            
            // Make HTTP request using relative path (Laravel will resolve the base URL)
            $response = Http::withHeaders($headers);
            
            if (!empty($params)) {
                $response = $response->get($endpoint, $params);
            } else {
                $response = $response->get($endpoint);
            }
            
            // Check if response is successful
            if ($response->successful()) {
                $data = $response->json();
                return isset($data['data']) ? $data['data'] : $data;
            } else {
                // Log error for debugging
                Log::error('API Request Failed', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            // Log exception for debugging
            Log::error('API Request Exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function create(Request $request)
    {
        // Get user data and token from session
        $userData = Session::get('user_data');
        $token = Session::get('token');
        dd($userData);

        // Check if user is logged in and has a valid session
        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        // Validate user role (admin or staff)
        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        // Validating input
        $validated = $request->validate([
            'tagihan' => 'required|string',
            'mahasiswa' => 'required|integer',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        
        try {
            // Step 1: Get data from the enrollment and periode pembayaran
            $mahasiswa = DB::table('enrollment_mahasiswa')->where('id', $validated['mahasiswa'])->first();
            $periode = DB::table('periode_pembayaran')->where('nama_periode', $validated['tagihan'])->first();

            dd($mahasiswa);
            if (!$mahasiswa) {
                return back()->withErrors(['error' => 'Data mahasiswa tidak ditemukan.']);
            }

            if (!$periode) {
                return back()->withErrors(['error' => 'Periode pembayaran tidak ditemukan.']);
            }

            
            // Step 2: Insert into ukt_semester table
            $uktSemester = DB::table('ukt_semester')->insertGetId([
                'id_enrollment' => $mahasiswa->id,
                'id_periode_pembayaran' => $periode->id,
                'jumlah_ukt' => $mahasiswa->golongan_ukt->nominal,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Step 3: Insert into pembayaran_ukt_semester table
            DB::table('pembayaran_ukt_semester')->insert([
                'id_enrollment' => $mahasiswa->id,
                'id_ukt_semester' => $uktSemester,
                'id_jenis_pembayaran' => 1, // Default to '1'
                'total_cicilan' => 1, // Default to 1
                'nominal_tagihan' => $mahasiswa->golongan_ukt->nominal,
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'status' => 'belum_bayar',
                'id_pengajuan_cicilan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('staff.buat-tagihan-ukt')->with('success', 'Tagihan UKT berhasil dibuat.');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Create Tagihan UKT Error', [
                'error' => $e->getMessage(),
                'user_id' => $userData['id'] ?? null,
                'request_data' => $validated
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat tagihan UKT. Silakan coba lagi.']);
        }
    }
}
