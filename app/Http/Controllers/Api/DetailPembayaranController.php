<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = DetailPembayaran::with([
            'pembayaranUktSemester.uktSemester.enrollment.mahasiswa',
            'pembayaranUktSemester.uktSemester.periodePembayaran',
            'verifiedBy'
        ])->orderByDesc('id');

        if ($request->has('nim')) {
            $query->whereHas('pembayaranUktSemester.uktSemester.enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Detail Pembayaran ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {   
        
        $rules = [
            'id_pembayaran_ukt_semester' => 'required|exists:pembayaran_ukt_semester,id',
            'nominal' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string|max:255',
            'kode_referensi' => 'nullable|string|max:255',
            'bukti_pembayaran_path' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,verified,rejected',
            'verified_by' => 'nullable|exists:staff,id',
            'verified_at' => 'nullable|date',
            'catatan' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $detail = DetailPembayaran::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menambahkan Detail Pembayaran',
            'data' => $detail
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $query = DetailPembayaran::with([
            'pembayaranUktSemester.uktSemester.enrollment.mahasiswa',
            'pembayaranUktSemester.uktSemester.periodePembayaran',
            'verifiedBy'
        ])->where('id', $id);

        if ($request->has('nim')) {
            $query->whereHas('pembayaranUktSemester.uktSemester.enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $detail = $query->first();

        if (!$detail) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $detail
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $detail = DetailPembayaran::find($id);

        if (!$detail) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('id_pembayaran_ukt_semester')) {
            $rules['id_pembayaran_ukt_semester'] = 'exists:pembayaran_ukt_semester,id';
        }
        if ($request->has('nominal')) {
            $rules['nominal'] = 'numeric|min:0';
        }
        if ($request->has('tanggal_pembayaran')) {
            $rules['tanggal_pembayaran'] = 'date';
        }
        if ($request->has('metode_pembayaran')) {
            $rules['metode_pembayaran'] = 'string|max:255';
        }
        if ($request->has('kode_referensi')) {
            $rules['kode_referensi'] = 'nullable|string|max:255';
        }
        if ($request->has('bukti_pembayaran_path')) {
            $rules['bukti_pembayaran_path'] = 'nullable|string|max:255';
        }
        if ($request->has('status')) {
            $rules['status'] = 'in:pending,verified,rejected';
        }
        if ($request->has('verified_by')) {
            $rules['verified_by'] = 'nullable|exists:staff,id';
        }
        if ($request->has('verified_at')) {
            $rules['verified_at'] = 'nullable|date';
        }
        if ($request->has('catatan')) {
            $rules['catatan'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $detail->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Update Detail Pembayaran',
            'data' => $detail
        ], 200);
    }

    public function destroy($id)
    {
        $detail = DetailPembayaran::find($id);

        if (!$detail) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $detail->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menghapus Detail Pembayaran'
        ], 200);
    }
}
