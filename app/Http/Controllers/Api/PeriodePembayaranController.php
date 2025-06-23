<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PeriodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodePembayaranController extends Controller
{
    public function index()
    {
        $data = PeriodePembayaran::with('tahunAkademik')->orderByDesc('id')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Periode Pembayaran Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_tahun_akademik' => 'required|exists:tahun_akademik,id',
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|string|in:aktif,non-aktif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Periode Pembayaran',
                'data' => $validator->errors()
            ], 400);
        }

        $periode = PeriodePembayaran::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Periode Pembayaran',
            'data' => $periode
        ], 201);
    }

    public function show($id)
    {
        $periode = PeriodePembayaran::with('tahunAkademik')->find($id);

        if (!$periode) {
            return response()->json([
                'status' => false,
                'message' => 'Periode Pembayaran Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Periode Pembayaran Ditemukan',
            'data' => $periode
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodePembayaran::find($id);

        if (!$periode) {
            return response()->json([
                'status' => false,
                'message' => 'Periode Pembayaran Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('id_tahun_akademik')) {
            $rules['id_tahun_akademik'] = 'exists:tahun_akademik,id';
        }

        if ($request->has('nama_periode')) {
            $rules['nama_periode'] = 'string|max:255';
        }

        if ($request->has('tanggal_mulai')) {
            $rules['tanggal_mulai'] = 'date';
        }

        if ($request->has('tanggal_selesai')) {
            $rules['tanggal_selesai'] = 'date|after_or_equal:tanggal_mulai';
        }

        if ($request->has('status')) {
            $rules['status'] = 'string|in:aktif,non-aktif';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Periode Pembayaran',
                'data' => $validator->errors()
            ], 400);
        }

        $periode->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Periode Pembayaran',
            'data' => $periode
        ], 200);
    }

    public function destroy($id)
    {
        $periode = PeriodePembayaran::find($id);

        if (!$periode) {
            return response()->json([
                'status' => false,
                'message' => 'Periode Pembayaran Tidak Ditemukan'
            ], 404);
        }

        $periode->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Periode Pembayaran'
        ], 200);
    }
}
