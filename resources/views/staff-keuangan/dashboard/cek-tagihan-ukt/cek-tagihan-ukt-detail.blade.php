@extends('layouts.staff-app')

@section('title', 'Detail Tagihan UKT - SIMAKU')

@section('header', 'Detail Tagihan UKT')

@section('header_button')
<a href="{{ route('staff.cek-tagihan-ukt') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left mr-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Nama Lengkap :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailTagihan['enrollment']['mahasiswa']['nama_lengkap'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>NIM :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailTagihan['enrollment']['mahasiswa']['nim'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Program Studi :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailTagihan['enrollment']['program_studi']['nama_prodi'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Status Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                               <p class="text-dark mb-0">{{ ucwords(str_replace('_', ' ', $detailTagihan['status'])) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Pembayaran --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Tagihan</th>
                                <th>Semester</th>
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Total Tagihan</th>
                                <th>Total Dibayar</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $id = $detailTagihan['id'];
                            $periode = $detailTagihan['periode_pembayaran']['nama_periode'] ?? '-';
                            $tanggal_terbit = \Carbon\Carbon::parse($detailTagihan['periode_pembayaran']['tanggal_mulai'] ?? '-')->format('d-M-Y');
                            $tanggal_jatuh_tempo = \Carbon\Carbon::parse($detailTagihan['periode_pembayaran']['tanggal_selesai'] ?? '-')->format('d-M-Y');
                            $jumlah_ukt = number_format($detailTagihan['jumlah_ukt'], 0, ',', '.');

                            // Ambil metode terakhir (jika ada)
                            if (!empty($detailTagihan['pembayaran']) && is_array($detailTagihan['pembayaran'])) {
                                $last = end($detailTagihan['pembayaran']);
                                $metode = $last['metode_pembayaran'] ?? '-';
                            }
                        @endphp
                        @php
                            // Ambil nilai jumlah UKT
                            $jumlahUkt = isset($detailTagihan['jumlah_ukt']) ? (float) $detailTagihan['jumlah_ukt'] : 0;

                            // Hitung total dibayar dari status pembayaran
                            $totalDibayar = 0;

                            if (!empty($detailTagihan['pembayaran']) && is_array($detailTagihan['pembayaran'])) {
                                foreach ($detailTagihan['pembayaran'] as $pembayaran) {
                                    if (isset($pembayaran['status']) && $pembayaran['status'] === 'terbayar') {
                                        $nominal = isset($pembayaran['nominal_tagihan']) ? (float) $pembayaran['nominal_tagihan'] : 0;
                                        $totalDibayar += $nominal;
                                    }
                                }
                            }

                            // Tentukan status dan warna badge
                            if ($totalDibayar >= $jumlahUkt && $jumlahUkt > 0) {
                                $statusText = 'Sudah Lunas';
                                $statusBadge = 'success'; // hijau
                            } else {
                                $statusText = 'Belum Lunas';
                                $statusBadge = 'danger'; // merah
                            }
                        @endphp
                        @php
                            $total_dibayar = 0;
                            if (!empty($detailTagihan['pembayaran']) && is_array($detailTagihan['pembayaran'])) {
                                foreach ($detailTagihan['pembayaran'] as $pembayaran) {
                                    if (isset($pembayaran['status']) && $pembayaran['status'] === 'terbayar') {
                                        $nominal = isset($pembayaran['nominal_tagihan']) ? (float) $pembayaran['nominal_tagihan'] : 0;
                                        $total_dibayar += $nominal;
                                    }
                                }
                            }

                            $total_dibayar = number_format($total_dibayar, 0, ',', '.');
                        @endphp
                        @php
                            // Default metode pembayaran
                            $metodePembayaran = '-';

                            if (!empty($detailTagihan['pembayaran']) && is_array($detailTagihan['pembayaran'])) {
                                $jumlahPembayaran = count($detailTagihan['pembayaran']);

                                if ($jumlahPembayaran > 1) {
                                    $metodePembayaran = 'Cicilan';
                                } elseif ($jumlahPembayaran === 1) {
                                    $metodePembayaran = 'Kontan';
                                }
                            }
                        @endphp

                            <tr>
                                <td>{{ 'INV' . str_pad($id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $periode }}</td>
                                <td>{{ $tanggal_terbit }}</td>
                                <td>{{ $tanggal_jatuh_tempo }}</td>
                                <td>Rp {{ $jumlah_ukt }}</td>
                                <td>Rp {{ $total_dibayar }}</td>
                                <td><span class="badge badge-{{ $statusBadge }}">{{ $statusText }}</span></td>
                                <td class="d-flex justify-content-center"><span class="badge bg-info">{{ strtoupper ($metodePembayaran) }}</span></td>
                                {{-- <td><span class="badge bg-info">{{ strtoupper($metode) }}</span></td> --}}
                            </tr>
                        </tbody>
                    </table>
                </div>


                {{-- <div class="mt-4">
                    <h5>Riwayat Pembayaran</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>10-Feb-2024</td>
                                    <td>Rp 1.300.000,00</td>
                                    <td>Transfer Bank</td>
                                    <td><span class="badge-diverifikasi">Terverifikasi</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>18-Feb-2024</td>
                                    <td>Rp 1.300.000,00</td>
                                    <td>Transfer Bank</td>
                                    <td><span class="badge-diverifikasi">Terverifikasi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> --}}

                {{-- <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Bank:</strong> Bank Mandiri</p>
                                <p><strong>No. Rekening:</strong> 1234-5678-9012-3456</p>
                                <p><strong>Atas Nama:</strong> Universitas XYZ</p>
                                <p><small>* Harap sertakan No. Tagihan pada keterangan transfer</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title">Catatan</h5>
                            </div>
                            <div class="card-body">
                                <p>Pembayaran telah diverifikasi dan lunas.</p>
                                <p><small>Diverifikasi oleh: Staff Keuangan pada 18-Feb-2024</small></p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection