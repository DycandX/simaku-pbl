@extends('layouts.app')

@section('title', 'Lihat Tagihan UKT - SIMAKU')

@section('header', 'Lihat Tagihan UKT')


@section('content')
<!-- Back Button -->
<div class="mt-3 mb-3">
    <a href="/lihat-tagihan-ukt" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>               
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Informasi Tagihan -->
                <div class="row">
                    <div class="col-md-7">
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>No Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ isset($uktSemester['id']) ? 'INV' . str_pad($uktSemester['id'], 5, '0', STR_PAD_LEFT) : '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Mahasiswa :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $uktSemester['enrollment']['mahasiswa']['nama_lengkap'] ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>NIM :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $uktSemester['enrollment']['mahasiswa']['nim'] ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Semester :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $uktSemester['periode_pembayaran']['nama_periode'] ?? '-' }}</p>
                            </div>
                        </div>

                        @php
                            $statusLunas = 'terbayar';
                            if (!empty($uktSemester['pembayaran'])) {
                                foreach ($uktSemester['pembayaran'] as $pembayaran) {
                                    if ($pembayaran['status'] != 'terbayar') {
                                        $statusLunas = 'belum_bayar';
                                        break;
                                    }
                                }
                            } else {
                                $statusLunas = '-';
                            }
                        @endphp

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Daftar Ulang :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($statusLunas == 'terbayar')
                                    <span class="badge badge-success">Sudah Lunas</span>
                                @elseif($statusLunas == 'belum_bayar')
                                    <span class="badge badge-danger">Belum Bayar</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tanggal Terbit :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">
                                    {{ isset($uktSemester['periode_pembayaran']['tanggal_mulai']) 
                                        ? \Carbon\Carbon::parse($uktSemester['periode_pembayaran']['tanggal_mulai'])->translatedFormat('d F Y')
                                        : '-' 
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tanggal Jatuh Tempo :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">
                                    {{ isset($uktSemester['periode_pembayaran']['tanggal_selesai']) 
                                        ? \Carbon\Carbon::parse($uktSemester['periode_pembayaran']['tanggal_selesai'])->translatedFormat('d F Y')
                                        : '-' 
                                    }}
                                </p>
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Status Verifikasi:</strong></p>
                            </div>
                            <div class="col-8">
                                @php
                                    $status = $uktSemester['pembayaran'][0]['detail_pembayaran'][0]['status'] ?? null;
                                @endphp

                                @if($status === 'verified')
                                    <span class="badge badge-success">Berhasil diverifikasi</span>
                                @elseif($status === 'rejected')
                                    <span class="badge badge-danger">Pembayaran ditolak</span>
                                @elseif($status === 'pending')
                                    <span class="badge badge-warning">Menunggu diverifikasi</span>
                                @else
                                    <span class="badge badge-secondary">Status tidak diketahui</span>
                                @endif
                            </div>
                        </div> --}}
{{--                         
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Status Payment :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($detailPembayaran['status'] == 'pending')
                                    <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @elseif($detailPembayaran['status'] == 'verified')
                                    <span class="badge badge-success">Sudah Lunas</span>
                                @elseif($detailPembayaran['status'] == 'rejected')
                                    <span class="badge badge-danger">Pembayaran Ditolak</span>
                                @else
                                    <span class="badge badge-secondary">Status Tidak Diketahui</span>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Dibayar Melalui :</strong></p>
                            </div>
                            <div class="col-8">
                                @if($detailPembayaran['metode_pembayaran'] != '-')
                                <span class="badge badge-info">{{ $detailPembayaran['metode_pembayaran'] }}</span>
                                @else
                                <p class="text-dark mb-0">{{ $detailPembayaran['metode_pembayaran'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i>  Download Tagihan
                                </a>
                            </div>
                        </div>
                    <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Tagihan :</strong></p>
                            </div>
                            <div class="col-8">
                            @php
                                    $sudahAjukanCicilan = false;

                                    // Cek jika ada lebih dari 1 pembayaran (artinya cicilan sudah diajukan)
                                    if (!empty($uktSemester['pembayaran']) && count($uktSemester['pembayaran']) > 1) {
                                        $sudahAjukanCicilan = true;
                                    }
                                @endphp

                                <div>
                                    {{-- Tombol Pembayaran Langsung selalu ditampilkan --}}
                                    <a href="{{ route('pengajuan.cicilan', ['id' => $uktSemester['id']]) }}" class="btn btn-primary btn-sm">
                                        Pembayaran Langsung
                                    </a>

                                    {{-- Tampilkan tombol Ajukan Cicilan jika belum pernah mengajukan --}}
                                    @if(!$sudahAjukanCicilan)
                                        <a href="{{ route('pengajuan.cicilan', ['id' => $uktSemester['id']]) }}" class="btn btn-success btn-sm">
                                            Ajukan Cicilan
                                        </a>
                                    @endif
                                
                            </div>
                        </div>

{{--                         
                        <div class="row mb-3">
                            <div class="col-">
                                <p class="mb-0"><strong>Pilih Metode Pembayaran :</strong></p>
                            </div>
                            <div class="col-12">
                                @php
                                    $sudahAjukanCicilan = false;

                                    // Cek jika ada lebih dari 1 nominal_tagihan (artinya cicilan sudah diajukan)
                                    if (!empty($uktSemester['pembayaran']) && count($uktSemester['pembayaran']) > 1) {
                                        $sudahAjukanCicilan = true;
                                    }
                                @endphp

                                @if(!$uktSemester['is_fully_paid'])
                                    <div>
                                        <a href="{{ route('pengajuan.cicilan', ['id' => $uktSemester['id']]) }}" class="btn btn-primary btn-sm">
                                            Pembayaran Langsung
                                        </a>

                                        @if(!$sudahAjukanCicilan)
                                            <a href="{{ route('pengajuan.cicilan', ['id' => $uktSemester['id']]) }}" class="btn btn-success btn-sm">
                                                Ajukan Cicilan
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-dark mb-0">Sudah pengajuan cicilan</p>
                                @endif
                            </div>
                        </div> --}}
                        
                     </div>
                </div>


                @foreach($uktSemester['pembayaran'] as $pembayaran)
                    @php
                        // Total tagihan dari masing-masing pembayaran
                        $totalTagihan = $pembayaran['nominal_tagihan'];

                        // Ambil detail pembayaran pertama (jika ada)
                        $detail = $pembayaran['detail_pembayaran'][0] ?? null;

                        // Default
                        $terbayar = 0;
                        $statusVerifikasi = $detail['status'] ?? null;
                        $metodePembayaran = $detail['metode_pembayaran'] ?? '-';

                        // Jika statusnya verified, hitung sebagai pembayaran
                        if ($detail && strtolower($statusVerifikasi) === 'verified') {
                            $terbayar = $detail['nominal'];
                        }

                        // Hitung belum dibayar
                        $belumDibayar = (float) $totalTagihan - (float) $terbayar;
                    @endphp

                    <div class="table-responsive mb-4 mt-3">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>ID Pembayaran</th>
                                    <th>Tagihan</th>
                                    <th>Terbayar</th>
                                    <th>Belum Dibayar</th>
                                    <th>Status Verifikasi</th>
                                    <th>Metode Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>{{ $pembayaran['id'] }}</td>
                                    <td>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                    <td class="text-success">Rp{{ number_format($terbayar, 0, ',', '.') }}</td>
                                    <td class="text-danger">Rp{{ number_format($belumDibayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if($statusVerifikasi === 'verified')
                                            <span class="badge badge-success">Berhasil diverifikasi</span>
                                        @elseif($statusVerifikasi === 'rejected')
                                            <span class="badge badge-danger">Pembayaran ditolak</span>
                                        @elseif($statusVerifikasi === 'pending')
                                            <span class="badge badge-warning">Menunggu diverifikasi</span>
                                        @else
                                            <span class="badge badge-secondary">Belum ada pembayaran</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($metodePembayaran !== '-')
                                            <span class="badge badge-info">{{ $metodePembayaran }}</span>
                                        @else
                                            <p class="text-dark mb-0">-</p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforeach

{{-- 
                @php
                    // Ambil total tagihan
                    $totalTagihan = $detailPembayaran['pembayaran_ukt_semester']['ukt_semester']['jumlah_ukt'];

                    // Default terbayar 0
                    $terbayar = 0;

                    // Cek jika pembayaran ada dan status-nya verified
                    if (
                        isset($detailPembayaran['nominal']) &&
                        $detailPembayaran['pembayaran_ukt_semester']['status'] === 'terbayar'
                    ) {
                        $terbayar = $detailPembayaran['nominal'];
                    }

                    // Hitung belum dibayar
                    $belumDibayar = (float) $totalTagihan - (float) $terbayar;
                @endphp

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
                                <td class="text-center">Rp{{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                <td class="text-center text-success">Rp{{ number_format($terbayar, 0, ',', '.') }}</td>
                                <td class="text-center text-danger">Rp{{ number_format($belumDibayar, 0, ',', '.') }}</td>
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
                                <p class="text-dark mb-0">{{ $detailPembayaran['kode_referensi'] }}</p>
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
                </div> --}}

                {{-- <!-- Upload Bukti Pembayaran Section -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('upload-bukti-pembayaran', ['id' => $detailPembayaran ['id']]) }}" class="btn btn-primary" style="min-width: 220px;">
                                <i class="fas fa-plus"></i> Tambah Bukti Pembayaran
                            </a>
                        </div> --}}

                        <!-- Tabel Upload Bukti Pembayaran -->
                        {{-- <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Bank Pengirim</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($detailPembayaran['pembayaran_ukt_semester']['status']== 'terbayar')
                                    <tr>
                                        <td class="text-center">01</td>
                                        <td>{{ $pembayaranUkt['mahasiswa'] ['nama_lengkap'] }}</td>
                                        <td>BANK {{ $detailPembayaran['metode_pembayaran'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($detailPembayaran['tanggal_pembayaran'])->translatedFormat('d F Y') }}</td>
                                        <td>Rp{{ number_format($detailPembayaran['nominal'], 0, ',', '.') }}</td>
                                        
                                        <td>-</td>
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
                        </div> --}}
                    </div>
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