<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCicilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanCicilanController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanCicilan::with([
            'enrollment.mahasiswa',
            'enrollment.programStudi',
            'enrollment.golonganUkt',
            'enrollment.kelas',
            'enrollment.tingkat',
            'enrollment.tahunAkademik',
            'uktSemester.periodePembayaran',
            'uktSemester.pembayaran',
            'pembayaran.detailPembayaran',
            'approver'
        ])->orderByDesc('id');

        if ($request->has('nim')) {
            $query->whereHas('enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data Pengajuan Cicilan ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_enrollment' => 'required|exists:enrollment_mahasiswa,id',
            'id_ukt_semester' => 'required|exists:ukt_semester,id',
            'jumlah_angsuran_diajukan' => 'required|integer|min:1',
            'alasan_pengajuan' => 'nullable|string',
            'file_path' => 'required|string|max:255',
            'status' => 'in:pending,approved,rejected',
            'approved_by' => 'nullable|exists:staff,id',
            'approved_at' => 'nullable|date',
            'catatan_approval' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $pengajuan = PengajuanCicilan::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menambahkan Pengajuan Cicilan',
            'data' => $pengajuan
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $query = PengajuanCicilan::with([
            'enrollment.mahasiswa',
            'enrollment.programStudi',
            'enrollment.golonganUkt',
            'enrollment.kelas',
            'enrollment.tingkat',
            'enrollment.tahunAkademik',
            'uktSemester.periodePembayaran',
            'uktSemester.pembayaran',
            'pembayaran.detailPembayaran',
            'approver'
        ])->where('id', $id);

        if ($request->has('nim')) {
            $query->whereHas('enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $pengajuan = $query->first();

        if (!$pengajuan) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $pengajuan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanCicilan::find($id);

        if (!$pengajuan) {
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
        if ($request->has('jumlah_angsuran_diajukan')) {
            $rules['jumlah_angsuran_diajukan'] = 'integer|min:1';
        }
        if ($request->has('jumlah_angsuran_disetujui')) {
            $rules['jumlah_angsuran_disetujui'] = 'nullable|integer|min:1';
        }
        if ($request->has('alasan_pengajuan')) {
            $rules['alasan_pengajuan'] = 'nullable|string';
        }
        if ($request->has('file_path')) {
            $rules['file_path'] = 'required|string|max:255';
        }
        if ($request->has('status')) {
            $rules['status'] = 'in:pending,approved,rejected';
        }
        if ($request->has('approved_by')) {
            $rules['approved_by'] = 'nullable|exists:staff,id';
        }
        if ($request->has('approved_at')) {
            $rules['approved_at'] = 'nullable|date';
        }
        if ($request->has('catatan_approval')) {
            $rules['catatan_approval'] = 'nullable|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $pengajuan->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Update Pengajuan Cicilan',
            'data' => $pengajuan
        ], 200);
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanCicilan::find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $pengajuan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menghapus Pengajuan Cicilan'
        ], 200);
    }
}
