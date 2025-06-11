<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PembayaranUktSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranUktSemesterController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranUktSemester::with([
            'enrollment.mahasiswa',
            'uktSemester.periodePembayaran',
            'jenisPembayaran',
            'pengajuanCicilan'
        ])->orderByDesc('id');

        if ($request->has('nim')) {
            $query->whereHas('enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'Data Pembayaran UKT Semester Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_enrollment' => 'required|exists:enrollment_mahasiswa,id',
            'id_ukt_semester' => 'required|exists:ukt_semester,id',
            'id_jenis_pembayaran' => 'required|exists:jenis_pembayaran,id',
            'total_cicilan' => 'required|integer|min:1',
            'nominal_tagihan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'status' => 'required|in:belum_bayar,terbayar,cancelled,overdue',
            'id_pengajuan_cicilan' => 'nullable|exists:pengajuan_cicilan,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Data Pembayaran UKT',
                'data' => $validator->errors()
            ], 400);
        }

        $pembayaran = PembayaranUktSemester::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menambahkan Data Pembayaran UKT',
            'data' => $pembayaran
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranUktSemester::find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $rules = [];

        if ($request->has('id_enrollment')) {
            $rules['id_enrollment'] = 'exists:enrollment_mahasiswa,id';
        }

        if ($request->has('id_ukt_semester')) {
            $rules['id_ukt_semester'] = 'exists:ukt_semester,id';
        }

        if ($request->has('id_jenis_pembayaran')) {
            $rules['id_jenis_pembayaran'] = 'exists:jenis_pembayaran,id';
        }

        if ($request->has('total_cicilan')) {
            $rules['total_cicilan'] = 'integer|min:1';
        }

        if ($request->has('nominal_tagihan')) {
            $rules['nominal_tagihan'] = 'numeric|min:0';
        }

        if ($request->has('tanggal_jatuh_tempo')) {
            $rules['tanggal_jatuh_tempo'] = 'date';
        }

        if ($request->has('status')) {
            $rules['status'] = 'in:belum_bayar,terbayar,cancelled,overdue';
        }

        if ($request->has('id_pengajuan_cicilan')) {
            $rules['id_pengajuan_cicilan'] = 'nullable|exists:pengajuan_cicilan,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Update Data Pembayaran UKT',
                'data' => $validator->errors()
            ], 400);
        }

        $pembayaran->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Pembayaran UKT',
            'data' => $pembayaran
        ], 200);
    }

    public function show($id)
    {
        $pembayaran = PembayaranUktSemester::with(['enrollment.mahasiswa',
            'uktSemester.periodePembayaran',
            'jenisPembayaran',
            'pengajuanCicilan'])->find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $pembayaran
        ], 200);
    }

     public function destroy($id)
    {
        $pembayaran = PembayaranUktSemester::find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $pembayaran->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Menghapus Data Pembayaran UKT'
        ], 200);
    }
}
