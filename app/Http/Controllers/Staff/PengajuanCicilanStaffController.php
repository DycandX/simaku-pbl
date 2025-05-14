<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengajuanCicilanStaffController extends Controller
{
    public function         x()
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
            // ... other data entries ...
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
        // Method untuk menampilkan detail pengajuan cicilan
        // Updated view path
        return view('staff-keuangan.pengajuan-cicilan.detail', compact('id'));
    }

    // Other methods remain the same...
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