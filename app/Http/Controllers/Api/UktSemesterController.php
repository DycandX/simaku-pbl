<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UktSemester;
use App\Models\EnrollmentMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UktSemesterController extends Controller
{
    public function index(Request $request)
    {
        $query = UktSemester::with([ 'enrollment.mahasiswa',
        'enrollment.programStudi',
        'enrollment.golonganUkt',
        'enrollment.kelas',
        'enrollment.tingkat',
        'enrollment.tahunAkademik',
        'periodePembayaran',
        'pembayaran.detailPembayaran',
        'pengajuanCicilan'])
            ->orderByDesc('id');

        if ($request->has('nim')) {
            $query->whereHas('enrollment.mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get();

        return response()->json([
            'status' => true,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data UKT Semester ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_enrollment' => 'required|exists:enrollment_mahasiswa,id',
            'id_periode_pembayaran' => 'required|exists:periode_pembayaran,id',
            'jumlah_ukt' => 'required|numeric|min:0',
            'status' => 'in:aktif,tidak_aktif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $uktSemester = UktSemester::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menambahkan Data UKT Semester',
            'data' => $uktSemester
        ], 201);
    }

    public function show($id)
    {
        $data = UktSemester::with(['enrollment.mahasiswa', 'periodePembayaran', 'pembayaran', 'pengajuanCicilan', 'pembayaran.detailPembayaran'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $data->total_paid = $data->total_paid;
        $data->remaining_amount = $data->remaining_amount;
        $data->is_fully_paid = $data->isFullyPaid();

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $uktSemester = UktSemester::find($id);

        if (!$uktSemester) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $rules = [
            'id_enrollment' => 'exists:enrollment_mahasiswa,id',
            'id_periode_pembayaran' => 'exists:periode_pembayaran,id',
            'jumlah_ukt' => 'numeric|min:0',
            'status' => 'in:aktif,tidak_aktif'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $uktSemester->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Update Data UKT Semester',
            'data' => $uktSemester
        ], 200);
    }

    public function destroy($id)
    {
        $uktSemester = UktSemester::find($id);

        if (!$uktSemester) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan'
            ], 404);
        }

        $uktSemester->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Menghapus Data UKT Semester'
        ], 200);
    }
}
