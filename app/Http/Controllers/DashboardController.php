<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Untuk sementara tanpa data dari database
        $totalTagihan = 7800000.00;
        $belumTerbayar = 2600000.00;
        $sudahTerbayar = 5200000.00;

        // Data dummy untuk tabel
        $tagihan = [
            [
                'id' => 1,
                'nomor_tagihan' => 'INV000013408',
                'semester' => '2023/2024 - Gasal',
                'nominal_tagihan' => 2600000.00,
                'nominal_terbayar' => 2600000.00,
                'keterangan' => 'Kontan',
                'status' => 'lunas',
                'metode_pembayaran' => 'BNI'
            ],
            [
                'id' => 2,
                'nomor_tagihan' => 'INV000014590',
                'semester' => '2023/2024 - Genap',
                'nominal_tagihan' => 2600000.00,
                'nominal_terbayar' => 2600000.00,
                'keterangan' => 'Kontan',
                'status' => 'lunas',
                'metode_pembayaran' => 'BNI'
            ],
            [
                'id' => 3,
                'nomor_tagihan' => 'INV000016030',
                'semester' => '2024/2025 - Gasal',
                'nominal_tagihan' => 2600000.00,
                'nominal_terbayar' => 0,
                'keterangan' => null,
                'status' => 'ditagihkan',
                'metode_pembayaran' => 'BNI'
            ],
        ];

        return view('mahasiswa.dashboard', compact('totalTagihan', 'belumTerbayar', 'sudahTerbayar', 'tagihan'));
    }
}