<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CicilanController extends Controller
{
    /**
     * Menampilkan form pengajuan cicilan.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Ambil tagihan_id jika ada
        $tagihan_id = $request->tagihan_id;

        // Di sini Anda bisa menambahkan logika untuk mengambil data tagihan
        // berdasarkan tagihan_id jika diperlukan

        // Contoh: jika ada tagihan_id, ambil data tagihan dari database
        $tagihan = null;
        if ($tagihan_id) {
            // $tagihan = Tagihan::find($tagihan_id);

            // Untuk sementara kita gunakan data contoh
            $tagihan = [
                'id' => $tagihan_id,
                'nama_tagihan' => 'TAGIHAN UKT 2023 - Genap',
                'jumlah' => 5000000
            ];
        }

        return view('mahasiswa.pengajuan_cicilan', compact('tagihan'));
    }

    /**
     * Menyimpan pengajuan cicilan ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tagihan' => 'required',
            'angsuran' => 'required',
            'file' => 'required|file|mimes:pdf|max:2048'
        ]);

        // Simpan file yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('cicilan', $fileName, 'public');

            // Di sini Anda bisa menyimpan data pengajuan cicilan ke database
            // misalnya:
            // Cicilan::create([
            //     'mahasiswa_id' => Auth::id(),
            //     'tagihan_id' => $request->tagihan_id,
            //     'tagihan' => $request->tagihan,
            //     'angsuran' => $request->angsuran,
            //     'file_path' => $filePath,
            //     'status' => 'Menunggu Persetujuan'
            // ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Pengajuan cicilan berhasil dikirim dan sedang menunggu persetujuan.');
    }
}