@extends('layouts.app')

@section('title', 'Lihat Tagihan UKT - SIMAKU')

@section('header', 'Lihat Tagihan UKT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Informasi Tagihan -->
                <div class="row">
                    <div class="col-md-7">
                        <!-- Kolom Kiri - Informasi Utama -->
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>No Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['no_tagihan'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Mahasiswa :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['mahasiswa'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>NIM :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['nim'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Semester :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['semester'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Daftar Utang :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($tagihan['daftar_utang'] == 'Sudah')
                                <span class="badge badge-success">{{ $tagihan['daftar_utang'] }}</span>
                                @else
                                <span class="badge badge-danger">{{ $tagihan['daftar_utang'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tanggal Terbit :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['tanggal_terbit'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tanggal Jatuh Tempo :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['tanggal_jatuh_tempo'] }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Status :</strong></p>
                            </div>
                            <div class="col-8">
                                @if(str_contains($tagihan['status'], 'Belum'))
                                <span class="badge badge-warning">{{ $tagihan['status'] }}</span>
                                @else
                                <span class="badge badge-info">{{ $tagihan['status'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Status Payment :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($tagihan['status_payment'] == 'Sudah Lunas')
                                <span class="badge badge-success">{{ $tagihan['status_payment'] }}</span>
                                @else
                                <span class="badge badge-danger">{{ $tagihan['status_payment'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Dibayar Melalui :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($tagihan['dibayar_melalui'] != '-')
                                <span class="badge badge-info">{{ $tagihan['dibayar_melalui'] }}</span>
                                @else
                                <p class="text-dark mb-0">{{ $tagihan['dibayar_melalui'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <!-- Kolom Kanan - Download dan Detail Pembayaran -->
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Download Tagihan
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Pilih Metode Pembayaran :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($tagihan['status_payment'] == 'Belum Lunas')
                                <div>
                                    <a href="#" class="btn btn-primary btn-sm">
                                        Pembayaran Langsung
                                    </a>
                                    <a href="{{ route('pengajuan.cicilan', ['tagihan_id' => $tagihan['id']]) }}" class="btn btn-success btn-sm">
                                        Pembayaran Cicil
                                    </a>
                                </div>
                                @else
                                <p class="text-dark mb-0">-</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Detail Pembayaran :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($tagihan['status_payment'] == 'Sudah Lunas')
                                <div class="alert alert-success mb-0 py-2" role="alert">
                                    <i class="fas fa-check-circle"></i> <strong>TAGIHAN SUDAH DIBAYAR</strong><br>
                                    <small>Dibayar Pada Tanggal : {{ $tagihan['tanggal_bayar'] }}<br>
                                    melalui bank: {{ $tagihan['bank'] }}</small>
                                </div>
                                @elseif($tagihan['status_payment'] == 'Belum Lunas')
                                <div class="alert alert-danger mb-0 py-2" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> <strong>TAGIHAN BELUM DIBAYAR</strong><br>
                                    <small>Jatuh Tempo : {{ $tagihan['tanggal_jatuh_tempo'] }}</small>
                                </div>
                                @else
                                <p class="text-dark mb-0">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Tagihan -->
                <div class="table-responsive mb-4 mt-3">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Tagihan</th>
                                <th class="text-center">Terbayar</th>
                                <th class="text-center">Belum Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">{{ $tagihan['total'] }}</td>
                                <td class="text-center">{{ $tagihan['terbayar'] }}</td>
                                <td class="text-center text-danger">{{ $tagihan['belum_dibayar'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- VA Payment Info -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <p class="mb-0"><strong>No VA :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $tagihan['no_va'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Alert -->
                <div class="alert alert-warning mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> Nomor VA akan muncul setelah metode pembayaran dipilih, dan memerlukan beberapa waktu untuk memproses No VA
                </div>

                <!-- Notes -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <p class="mb-0"><strong>Catatan :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <p class="mb-0"><strong>Catatan Pembayaran :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Bukti Pembayaran Section -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="#" class="btn btn-primary" style="min-width: 220px;">
                                <i class="fas fa-plus"></i> Tambah Bukti Pembayaran
                            </a>
                        </div>

                        <!-- Tabel Upload Bukti Pembayaran -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Pengirim</th>
                                        <th>Bank Pengirim</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($tagihan['status_payment'] == 'Sudah Lunas')
                                    <tr>
                                        <td class="text-center">01</td>
                                        <td>{{ $tagihan['mahasiswa'] }}</td>
                                        <td>BANK {{ $tagihan['bank'] }}</td>
                                        <td>{{ $tagihan['tanggal_bayar'] }}</td>
                                        <td>{{ $tagihan['total'] }}</td>
                                        <td>BANK {{ $tagihan['bank'] }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-primary" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada bukti pembayaran</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-success {
        background-color: #28a745;
        color: #fff;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .table-bordered thead th {
        border-bottom: 2px solid #dee2e6;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }

    /* Make sure each section has proper spacing */
    .card-body p {
        margin-bottom: 0;
    }

    strong {
        font-weight: 600;
    }

    .thead-light th {
        background-color: #f8f9fc;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endsection