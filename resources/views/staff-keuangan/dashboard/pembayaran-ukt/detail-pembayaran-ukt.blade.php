@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT - SIMAKU')
@section('header', 'Pembayaran UKT')

@section('header_button')
<a href="{{ route('staff.pembayaran-ukt') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left mr-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="card-body">
                <h5 class="mb-4">Detail Pembayaran UKT</h5>

                {{-- Informasi Mahasiswa --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0 fw-bold">Nama Lengkap:</p>
                            </div>
                            <div class="col-8">
                                <p class="mb-0">{{ $detailPembayaran['pembayaran_ukt_semester']['ukt_semester']['enrollment']['mahasiswa']['nama_lengkap'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0 fw-bold">NIM:</p>
                            </div>
                            <div class="col-8">
                                <p class="mb-0">{{ $detailPembayaran['pembayaran_ukt_semester']['ukt_semester']['enrollment']['mahasiswa']['nim'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail Pembayaran --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No Tagihan</th>
                                <th>Semester</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Jumlah Dibayar</th>
                                <th>Bank Pengirim</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ 'INV' . str_pad($detailPembayaran['id'], 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $detailPembayaran['pembayaran_ukt_semester']['ukt_semester']['periode_pembayaran']['nama_periode'] ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($detailPembayaran['tanggal_pembayaran'])->translatedFormat('d M Y') }}</td>
                                <td>Rp {{ number_format($detailPembayaran['nominal'], 0, ',', '.') }}</td>
                                <td>{{ $detailPembayaran['metode_pembayaran'] }}</td>
                                <td>
                                    @php
                                        $status = $detailPembayaran['status'];
                                        $badgeColor = match($status) {
                                            'verified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'warning',
                                        };
                                        $statusText = match($status) {
                                            'verified' => 'Terverifikasi',
                                            'rejected' => 'Ditolak',
                                            default => 'Menunggu',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    @if($detailPembayaran['bukti_pembayaran_path'])
                                        <a href="{{ asset('storage/' . $detailPembayaran['bukti_pembayaran_path']) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('staff.pembayaran-ukt.update-status', $detailPembayaran['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="verified">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')" {{ $status === 'verified' ? 'disabled' : '' }}>
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('staff.pembayaran-ukt.update-status', $detailPembayaran['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?')" {{ $status === 'rejected' ? 'disabled' : '' }}>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




