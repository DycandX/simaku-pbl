<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengajuanCicilanStaffController extends Controller
{
    public function x()
    {
        // Data contoh pengajuan cicilan untuk ditampilkan di dashboard
        $pengajuanData = collect([
            [
                'nim' => '4.33.23.5.19',
                'nama' => 'Zirlda Syafira',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '2024-01-15',
                'jumlah_cicilan' => 2,
                'nominal_per_cicilan' => 2500000
            ],
            [
                'nim' => '4.33.23.5.20',
                'nama' => 'Fadhil Ramadhan',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '2024-01-16',
                'jumlah_cicilan' => 3,
                'nominal_per_cicilan' => 1750000
            ],
            [
                'nim' => '4.33.23.5.21',
                'nama' => 'Aisyah Hanifah',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '2024-01-17',
                'jumlah_cicilan' => 4,
                'nominal_per_cicilan' => 1250000
            ],
            [
                'nim' => '4.33.23.5.22',
                'nama' => 'Yudha Prasetyo',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '2024-01-18',
                'jumlah_cicilan' => 2,
                'nominal_per_cicilan' => 2500000
            ],
            [
                'nim' => '4.33.23.5.23',
                'nama' => 'Lestari Widya',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '2024-01-19',
                'jumlah_cicilan' => 3,
                'nominal_per_cicilan' => 1750000
            ],
            [
                'nim' => '4.33.23.5.24',
                'nama' => 'Ahmad Fauzi',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Belum Diverifikasi',
                'tanggal_pengajuan' => '2024-01-20',
                'jumlah_cicilan' => 4,
                'nominal_per_cicilan' => 1250000
            ],
            [
                'nim' => '4.33.23.5.25',
                'nama' => 'Siti Nurhaliza',
                'semester' => '2023 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Belum Diverifikasi',
                'tanggal_pengajuan' => '2024-01-21',
                'jumlah_cicilan' => 4,
                'nominal_per_cicilan' => 1250000
            ]
        ]);

        // Hitung summary status
        $statusSummary = [
            'diverifikasi' => $pengajuanData->where('status', 'Diverifikasi')->count(),
            'belum_diverifikasi' => $pengajuanData->where('status', 'Belum Diverifikasi')->count(),
            'ditolak' => $pengajuanData->where('status', 'Ditolak')->count(),
        ];

        // Data untuk dropdown filter
        $semesterOptions = $pengajuanData->pluck('semester')->unique()->values();
        $jurusanOptions = $pengajuanData->pluck('jurusan')->unique()->values();
        $prodiOptions = $pengajuanData->pluck('prodi')->unique()->values();

        // Updated view path to match the folder structure
        return view('staff-keuangan.dashboard.pengajuan-cicilan.pengajuan-cicilan', compact(
            'pengajuanData',
            'statusSummary',
            'semesterOptions',
            'jurusanOptions',
            'prodiOptions'
        ));
    }

    public function show($id)
    {
        // Simulasi data untuk detail pengajuan cicilan
        $pengajuanData = collect([
            [
                'nim' => '4.33.23.5.19',
                'nama' => 'Zirlda Syafira',
                'semester' => '2023/2024 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => '15 Januari 2024',
                'jumlah_cicilan' => 2,
                'nominal_per_cicilan' => 2500000
            ],
            [
                'nim' => '4.33.23.5.25',
                'nama' => 'Siti Nurhaliza',
                'semester' => '2023/2024 - Genap',
                'jurusan' => 'Administrasi Bisnis',
                'prodi' => 'D4 - Manajemen Bisnis Internasional',
                'status' => 'Belum Diverifikasi',
                'tanggal_pengajuan' => '21 Januari 2024',
                'jumlah_cicilan' => 4,
                'nominal_per_cicilan' => 1250000
            ]
        ]);

        // Temukan data pengajuan berdasarkan id
        // Dalam kasus nyata, kita akan query dari database
        $pengajuan = $pengajuanData->firstWhere('nim', $id) ?? $pengajuanData[1];

        // Siapkan data mahasiswa terpisah dari pengajuan
        $mahasiswa = [
            'nim' => $pengajuan['nim'],
            'nama' => $pengajuan['nama'],
            'jurusan' => $pengajuan['jurusan'],
            'prodi' => $pengajuan['prodi'],
            'semester' => $pengajuan['semester']
        ];

        // Updated view path to match the folder structure
        return view('staff-keuangan.dashboard.pengajuan-cicilan.pengajuan-cicilan-detail', compact(
            'pengajuan',
            'mahasiswa',
            'id'
        ));
    }

    public function approve(Request $request, $id)
    {
        // Method untuk approve pengajuan cicilan
        try {
            // Logic approve pengajuan cicilan
            // Update status menjadi 'Diverifikasi'

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cicilan berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi pengajuan cicilan'
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        // Method untuk reject pengajuan cicilan
        try {
            // Logic reject pengajuan cicilan
            // Update status menjadi 'Ditolak'

            // Mendapatkan alasan penolakan
            $reason = $request->input('reason');

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan cicilan berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan cicilan'
            ], 500);
        }
    }

    public function filter(Request $request)
    {
        // Method untuk filter data pengajuan cicilan
        $filters = $request->only(['semester', 'status', 'jurusan', 'search']);

        // Logic filter data pengajuan cicilan

        return response()->json(['filtered_data' => []]);
    }
}