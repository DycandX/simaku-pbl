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
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        $dataTagihan = $this->getApiData('/api/ukt-semester', [], $token);
        $periodePembayaran = $this->getApiData('/api/periode-pembayaran', [], $token);
        $mahasiswaData = $this->getApiData('/api/enrollment-mahasiswa', [], $token);

        // Cari periode pembayaran yang aktif
        $periodeAktif = collect($periodePembayaran)->firstWhere('status', 'aktif');

        $mahasiswaAktif = collect($mahasiswaData)->filter(function($enrollment) {
            return isset($enrollment['tahun_akademik']) &&
                   $enrollment['tahun_akademik']['status'] === 'aktif';
        })->values()->toArray();

        return view('staff-keuangan.dashboard.buat-tagihan-ukt.detail-buat-tagihan-ukt', [
            'dataTagihan' => $dataTagihan,
            'periodePembayaran' => $periodePembayaran,
            'periodeAktif' => $periodeAktif, // Tambahan periode aktif
            'mahasiswaData' => $mahasiswaAktif
        ]);
    }

    private function getApiData($endpoint, $params = [], $token = null)
    {
        try {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }

            $baseUrl = config('app.api_url');
            $fullUrl = rtrim($baseUrl, '/') . $endpoint;

            Log::info('Requesting API', ['url' => $fullUrl]);

            $response = Http::withHeaders($headers);

            $response = !empty($params)
                ? $response->get($fullUrl, $params)
                : $response->get($fullUrl);

            Log::info('API Response Raw', ['body' => $response->body()]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['data']) ? $data['data'] : $data;
            } else {
                Log::error('API Request Failed', [
                    'endpoint' => $fullUrl,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            Log::error('API Request Exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function create(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return redirect()->route('login')->withErrors(['error' => 'Harap login terlebih dahulu.']);
        }

        if (!in_array($userData['role'], ['admin', 'staff'])) {
            return redirect()->route('login')->withErrors(['error' => 'Akses ditolak.']);
        }

        $validated = $request->validate([
            'tanggal_jatuh_tempo' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Cari periode pembayaran aktif
            $periode = DB::table('periode_pembayaran')
                ->where('status', 'aktif')
                ->first();

            if (!$periode) {
                DB::rollback();
                return back()->withErrors(['error' => 'Tidak ada periode pembayaran yang aktif.']);
            }

            // Cari tahun akademik aktif
            $tahunAkademikAktif = DB::table('tahun_akademik')
                ->where('status', 'aktif')
                ->first();

            if (!$tahunAkademikAktif) {
                DB::rollback();
                return back()->withErrors(['error' => 'Tidak ada tahun akademik yang aktif.']);
            }

            // Ambil semua enrollment mahasiswa aktif beserta data yang diperlukan
            $enrollments = DB::table('enrollment_mahasiswa')
                ->join('golongan_ukt', 'enrollment_mahasiswa.id_golongan_ukt', '=', 'golongan_ukt.id')
                ->join('mahasiswa', 'enrollment_mahasiswa.id_mahasiswa', '=', 'mahasiswa.id')
                ->select(
                    'enrollment_mahasiswa.id as enrollment_id',
                    'enrollment_mahasiswa.id_mahasiswa',
                    'golongan_ukt.nominal as nominal_ukt',
                    'mahasiswa.nama_lengkap',
                    'mahasiswa.nim'
                )
                ->where('enrollment_mahasiswa.id_tahun_akademik', $tahunAkademikAktif->id)
                ->whereNotNull('golongan_ukt.nominal')
                ->where('golongan_ukt.nominal', '>', 0)
                ->get();

            if ($enrollments->isEmpty()) {
                DB::rollback();
                return back()->withErrors(['error' => 'Tidak ada mahasiswa yang terdaftar pada tahun akademik aktif atau tidak memiliki golongan UKT yang valid.']);
            }

            Log::info('Total enrollments found', ['count' => $enrollments->count()]);

            $sukses = 0;
            $gagal = 0;
            $sudahAda = 0;
            $errorDetails = [];

            // Loop untuk setiap mahasiswa
            foreach ($enrollments as $index => $enrollment) {
                try {
                    Log::info('Processing enrollment', [
                        'index' => $index + 1,
                        'enrollment_id' => $enrollment->enrollment_id,
                        'nim' => $enrollment->nim,
                        'nama' => $enrollment->nama_lengkap,
                        'nominal_ukt' => $enrollment->nominal_ukt
                    ]);

                    // Cek apakah sudah ada UKT untuk enrollment dan periode ini
                    $existingUkt = DB::table('ukt_semester')
                        ->where('id_enrollment', $enrollment->enrollment_id)
                        ->where('id_periode_pembayaran', $periode->id)
                        ->first();

                    if ($existingUkt) {
                        Log::info('UKT already exists', [
                            'enrollment_id' => $enrollment->enrollment_id,
                            'existing_ukt_id' => $existingUkt->id
                        ]);
                        $sudahAda++;
                        continue;
                    }

                    // Validasi nominal UKT
                    if (!$enrollment->nominal_ukt || $enrollment->nominal_ukt <= 0) {
                        Log::warning('Invalid nominal UKT', [
                            'enrollment_id' => $enrollment->enrollment_id,
                            'nominal_ukt' => $enrollment->nominal_ukt
                        ]);
                        $errorDetails[] = "NIM {$enrollment->nim} - {$enrollment->nama_lengkap}: Nominal UKT tidak valid";
                        $gagal++;
                        continue;
                    }

                    // Insert ke tabel ukt_semester
                    $uktSemesterId = DB::table('ukt_semester')->insertGetId([
                        'id_enrollment' => $enrollment->enrollment_id,
                        'id_periode_pembayaran' => $periode->id,
                        'jumlah_ukt' => $enrollment->nominal_ukt,
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('UKT Semester created', [
                        'ukt_semester_id' => $uktSemesterId,
                        'enrollment_id' => $enrollment->enrollment_id
                    ]);

                    // Insert ke tabel pembayaran_ukt_semester
                    $pembayaranId = DB::table('pembayaran_ukt_semester')->insertGetId([
                        'id_enrollment' => $enrollment->enrollment_id,
                        'id_ukt_semester' => $uktSemesterId,
                        'id_jenis_pembayaran' => 1, // Pastikan ID ini sesuai dengan data di tabel jenis_pembayaran
                        'total_cicilan' => 1,
                        'nominal_tagihan' => $enrollment->nominal_ukt,
                        'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                        'status' => 'belum_bayar',
                        'id_pengajuan_cicilan' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Pembayaran UKT created', [
                        'pembayaran_id' => $pembayaranId,
                        'ukt_semester_id' => $uktSemesterId
                    ]);

                    $sukses++;

                } catch (\Exception $e) {
                    Log::error('Error creating UKT for enrollment', [
                        'enrollment_id' => $enrollment->enrollment_id,
                        'nim' => $enrollment->nim,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errorDetails[] = "NIM {$enrollment->nim} - {$enrollment->nama_lengkap}: {$e->getMessage()}";
                    $gagal++;
                }
            }

            DB::commit();

            // Buat pesan hasil
            $message = "Proses pembuatan tagihan UKT selesai. ";
            $message .= "Total Mahasiswa: " . $enrollments->count() . ", ";
            $message .= "Berhasil: {$sukses}, ";
            $message .= "Gagal: {$gagal}, ";
            $message .= "Sudah Ada: {$sudahAda}";

            // Log hasil akhir
            Log::info('Batch UKT Creation Result', [
                'total_mahasiswa' => $enrollments->count(),
                'sukses' => $sukses,
                'gagal' => $gagal,
                'sudah_ada' => $sudahAda,
                'error_details' => $errorDetails
            ]);

            if ($sukses > 0) {
                $successMessage = $message;
                if (!empty($errorDetails)) {
                    $successMessage .= "\n\nDetail Error:\n" . implode("\n", $errorDetails);
                }
                return redirect()->route('staff.buat-tagihan-ukt')->with('success', $successMessage);
            } else {
                $errorMessage = $message;
                if (!empty($errorDetails)) {
                    $errorMessage .= "\n\nDetail Error:\n" . implode("\n", $errorDetails);
                }
                return back()->withErrors(['error' => $errorMessage]);
            }

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Create Batch Tagihan UKT Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userData['id'] ?? null,
                'request_data' => $validated
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat tagihan UKT: ' . $e->getMessage()]);
        }
    }

    public function preview(Request $request)
    {
        $userData = Session::get('user_data');
        $token = Session::get('token');

        if (!$userData || !$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $tahunAkademikAktif = DB::table('tahun_akademik')
                ->where('status', 'aktif')
                ->first();

            if (!$tahunAkademikAktif) {
                return response()->json(['error' => 'Tidak ada tahun akademik yang aktif'], 400);
            }

            // Ambil periode pembayaran aktif
            $periode = DB::table('periode_pembayaran')
                ->where('status', 'aktif')
                ->first();

            if (!$periode) {
                return response()->json(['error' => 'Tidak ada periode pembayaran yang aktif'], 400);
            }

            $enrollments = DB::table('enrollment_mahasiswa')
                ->join('golongan_ukt', 'enrollment_mahasiswa.id_golongan_ukt', '=', 'golongan_ukt.id')
                ->join('mahasiswa', 'enrollment_mahasiswa.id_mahasiswa', '=', 'mahasiswa.id')
                ->join('program_studi', 'enrollment_mahasiswa.id_program_studi', '=', 'program_studi.id')
                ->leftJoin('ukt_semester', function($join) use ($periode) {
                    $join->on('enrollment_mahasiswa.id', '=', 'ukt_semester.id_enrollment')
                         ->where('ukt_semester.id_periode_pembayaran', '=', $periode->id);
                })
                ->select(
                    'enrollment_mahasiswa.id as enrollment_id',
                    'mahasiswa.nim',
                    'mahasiswa.nama_lengkap',
                    'program_studi.nama_prodi',
                    'golongan_ukt.nominal as nominal_ukt',
                    'ukt_semester.id as existing_ukt'
                )
                ->where('enrollment_mahasiswa.id_tahun_akademik', $tahunAkademikAktif->id)
                ->whereNotNull('golongan_ukt.nominal')
                ->where('golongan_ukt.nominal', '>', 0)
                ->get();

            $data = [
                'total_mahasiswa' => $enrollments->count(),
                'sudah_ada_tagihan' => $enrollments->where('existing_ukt', '!=', null)->count(),
                'akan_dibuatkan' => $enrollments->where('existing_ukt', null)->count(),
                'tahun_akademik' => $tahunAkademikAktif->tahun_akademik . ' - ' . $tahunAkademikAktif->semester,
                'periode' => $periode->nama_periode,
                'mahasiswa' => $enrollments->map(function($item) {
                    return [
                        'nim' => $item->nim,
                        'nama' => $item->nama_lengkap,
                        'prodi' => $item->nama_prodi,
                        'nominal' => number_format($item->nominal_ukt, 0, ',', '.'),
                        'status' => $item->existing_ukt ? 'Sudah Ada' : 'Akan Dibuat'
                    ];
                })
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Preview Tagihan UKT Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Terjadi kesalahan saat memuat preview'], 500);
        }
    }
}