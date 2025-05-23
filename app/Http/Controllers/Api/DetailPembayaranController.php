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


    // public function show($id)
    // {
    //     $detail = DetailPembayaran::with(['pembayaranUktSemester', 'verifiedBy'])->find($id);

    //     if (!$detail) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data Tidak Ditemukan'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Data Ditemukan',
    //         'data' => $detail
    //     ], 200);
    // }

    // public function show($id)
    // {
    //     $item = DetailPembayaran::with([
    //         'pembayaranUktSemester.uktSemester.mahasiswa',
    //         'pembayaranUktSemester.uktSemester.periodePembayaran',
    //         'verifiedBy'
    //     ])->find($id);

    //     if (!$item) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data tidak ditemukan',
    //             'data' => null
    //         ]);
    //     }

    //     $uktSemester = $item->pembayaranUktSemester->uktSemester ?? null;
    //     $mahasiswa = $uktSemester->mahasiswa ?? null;

    //     // Optional: ambil nama semester
    //     $semester = $uktSemester->periodePembayaran->nama_periode ?? null;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Data Pembayaran UKT Semester Ditemukan',
    //         'data' => [
    //             'id' => $item->id,
    //             'nim' => $mahasiswa->nim ?? null,
    //             'id_ukt_semester' => $uktSemester->id ?? null,
    //             'nomor_cicilan' => $item->nomor_cicilan,
    //             'nominal_tagihan' => $item->nominal_tagihan,
    //             'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo,
    //             'status' => $item->status,
    //             'created_at' => $item->created_at,
    //             'updated_at' => $item->updated_at,
    //             'mahasiswa' => $mahasiswa,
    //             'ukt_semester' => $uktSemester,
    //             'nomor_tagihan' => 'INV' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
    //             'semester' => $semester,
    //             'total_tagihan' => $item->nominal_tagihan,
    //             'total_terbayar' => $item->status === 'sudah lunas' ? $item->nominal_tagihan : 0,
    //             'keterangan' => 'kontan'
    //         ]
    //     ]);
    // }
    public function show($id)
    {
        $item = DetailPembayaran::with([
            'pembayaranUktSemester.uktSemester.mahasiswa',
            'pembayaranUktSemester.uktSemester.periodePembayaran',
            'verifiedBy'
        ])->find($id); 

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Data Pembayaran UKT Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Pembayaran UKT Ditemukan',
            'data' => $item
        ]);
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
