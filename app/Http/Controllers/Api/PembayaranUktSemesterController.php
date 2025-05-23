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
            'mahasiswa',
            'uktSemester.periodePembayaran' // perhatikan ini
        ])->orderByDesc('id');

        // Filter berdasarkan NIM jika tersedia di query
        if ($request->has('nim')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->nim);
            });
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nim' => $item->nim,
                'id_ukt_semester' => $item->id_ukt_semester,
                'nomor_cicilan' => $item->nomor_cicilan,
                'nominal_tagihan' => $item->nominal_tagihan,
                'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo,
                'status' => $item->status === 'terbayar' ? 'sudah lunas' : 'belum lunas',
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'mahasiswa' => $item->mahasiswa,
                'ukt_semester' => $item->uktSemester,
                'nomor_tagihan' => 'INV' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                'semester' => optional($item->uktSemester->periodePembayaran)->nama_periode ?? '-',
                'total_tagihan' => $item->uktSemester->jumlah_ukt,
                'total_terbayar' => $item->status === 'terbayar' ? $item->nominal_tagihan : 0,
                'keterangan' => $item->nomor_cicilan == 1 ? 'kontan' : 'cicilan',
            ];
            return array_merge($original, $custom);
        });
        

        return response()->json([
            'status' => true,
            'message' => 'Data Pembayaran UKT Semester Ditemukan',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|exists:mahasiswa,nim',
            'id_ukt_semester' => 'required|exists:ukt_semester,id',
            'nomor_cicilan' => 'required|integer|min:1',
            'nominal_tagihan' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'status' => 'required|string|in:belum,terbayar,terlambat'
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

    public function show($id)
    {
        $pembayaran = PembayaranUktSemester::with(['mahasiswa', 'uktSemester'])->find($id);

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

        if ($request->has('nim')) {
            $rules['nim'] = 'exists:mahasiswa,nim';
        }

        if ($request->has('id_ukt_semester')) {
            $rules['id_ukt_semester'] = 'exists:ukt_semester,id';
        }

        if ($request->has('nomor_cicilan')) {
            $rules['nomor_cicilan'] = 'integer|min:1';
        }

        if ($request->has('nominal_tagihan')) {
            $rules['nominal_tagihan'] = 'numeric|min:0';
        }

        if ($request->has('tanggal_jatuh_tempo')) {
            $rules['tanggal_jatuh_tempo'] = 'date';
        }

        if ($request->has('status')) {
            $rules['status'] = 'string|in:belum,terbayar,terlambat';
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
