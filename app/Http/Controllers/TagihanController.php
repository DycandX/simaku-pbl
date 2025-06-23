<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function show($id)
    {
        // Data tagihan berdasarkan ID
        $tagihan = null;

        // Tagihan ID 1 - Sudah Lunas
        if ($id == 1) {
            $tagihan = [
                'id' => 1,
                'no_tagihan' => 'INV000013408',
                'mahasiswa' => 'Zirlda Syafira',
                'nim' => '4.33.23.4.22',
                'semester' => '2023 - Genap',
                'daftar_utang' => 'Sudah',
                'tanggal_terbit' => '29-Jan-2024',
                'tanggal_jatuh_tempo' => '2-Feb-2024',
                'status' => 'Diverifikasi',
                'status_payment' => 'Sudah Lunas',
                'dibayar_melalui' => 'BNI',
                'total' => 'Rp 2.600.000,00',
                'terbayar' => 'Rp 2.600.000,00',
                'belum_dibayar' => 'Rp 0,00',
                'no_va' => '9881099943323422',
                'tanggal_bayar' => '01 Feb 2024',
                'bank' => 'BNI'
            ];
        }
        // Tagihan ID 3 - Belum Lunas
        else if ($id == 3) {
            $tagihan = [
                'id' => 3,
                'no_tagihan' => 'INV000016030',
                'mahasiswa' => 'Zirlda Syafira',
                'nim' => '4.33.23.4.22',
                'semester' => '2024 - Gasal',
                'daftar_utang' => 'Belum',
                'tanggal_terbit' => '15-Apr-2024',
                'tanggal_jatuh_tempo' => '30-Apr-2024',
                'status' => 'Belum diverifikasi',
                'status_payment' => 'Belum Lunas',
                'dibayar_melalui' => '-',
                'total' => 'Rp 2.600.000,00',
                'terbayar' => 'Rp 0,00',
                'belum_dibayar' => 'Rp 2.600.000,00',
                'no_va' => '-',
                'tanggal_bayar' => '-',
                'bank' => '-'
            ];
        }
        // Default atau ID lainnya
        else {
            $tagihan = [
                'id' => 2,
                'no_tagihan' => 'INV000014590',
                'mahasiswa' => 'Zirlda Syafira',
                'nim' => '4.33.23.4.22',
                'semester' => '2023 - Genap',
                'daftar_utang' => 'Sudah',
                'tanggal_terbit' => '29-Jan-2024',
                'tanggal_jatuh_tempo' => '2-Feb-2024',
                'status' => 'Diverifikasi',
                'status_payment' => 'Sudah Lunas',
                'dibayar_melalui' => 'BNI',
                'total' => 'Rp 2.600.000,00',
                'terbayar' => 'Rp 2.600.000,00',
                'belum_dibayar' => 'Rp 0,00',
                'no_va' => '9881099943323422',
                'tanggal_bayar' => '01 Feb 2024',
                'bank' => 'BNI'
            ];
        }

        return view('mahasiswa.lihat_tagihan', compact('tagihan'));
    }

    public function index()
    {
        return view('mahasiswa.dashboard');
    }
}