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
            'pembayaranUktSemester.uktSemester.mahasiswa',
            'pembayaranUktSemester.uktSemester.periodePembayaran',
            'verifiedBy'
        ])->orderByDesc('id');

        // Filter berdasarkan NIM jika tersedia
        if ($request->has('nim')) {
            $query->whereHas('pembayaranUktSemester.uktSemester.mahasiswa', function ($q) use ($request) {
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
            'metode_pembayaran' => 'required|string',
            'kode_referensi' => 'nullable|string',
            'bukti_pembayaran_path' => 'nullable|string',
            'status' => 'in:pending,verified,rejected',
            'verified_by' => 'nullable|exists:staff,id',
            'verified_at' => 'nullable|date',
            'catatan' => 'nullable|string'
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


    public function show($id)
    {
        $detail = DetailPembayaran::with(['pembayaranUktSemester', 'verifiedBy'])->find($id);

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

        $rules = [
            'id_pembayaran_ukt_semester' => 'exists:pembayaran_ukt_semester,id',
            'nominal' => 'numeric|min:0',
            'tanggal_pembayaran' => 'date',
            'metode_pembayaran' => 'string',
            'kode_referensi' => 'nullable|string',
            'bukti_pembayaran_path' => 'nullable|string',
            'status' => 'in:pending,verified,rejected',
            'verified_by' => 'nullable|exists:staff,id',
            'verified_at' => 'nullable|date',
            'catatan' => 'nullable|string'
        ];

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
