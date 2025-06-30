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
                                <p class="mb-0"><strong>Metode Pembayaran :</strong></p>
                            </div>
                            <div class="col-8">
                            @php
                                $sudahAjukanCicilan = false;
                                $totalTagihan = 0;
                                $totalTerbayar = 0;

                                if (!empty($uktSemester['pembayaran'])) {
                                    foreach ($uktSemester['pembayaran'] as $pembayaran) {
                                        $totalTagihan += $pembayaran['nominal_tagihan'];

                                        // Hitung total pembayaran yang sudah terverifikasi
                                        foreach ($pembayaran['detail_pembayaran'] as $detail) {
                                            if (strtolower($detail['status'] ?? '') === 'verified') {
                                                $totalTerbayar += $detail['nominal'];
                                            }
                                        }
                                    }

                                    // Jika ada lebih dari satu pembayaran, maka cicilan sudah diajukan
                                    if (count($uktSemester['pembayaran']) > 1) {
                                        $sudahAjukanCicilan = true;
                                    }

                                    // Tapi kalau seluruh tagihan sudah terbayar, maka anggap tidak bisa ajukan cicilan
                                    if ($totalTerbayar >= $totalTagihan) {
                                        $sudahAjukanCicilan = true;
                                    }
                                }
                            @endphp
                                <div>
                                    {{-- Tombol Pembayaran Langsung selalu ditampilkan --}}
                                    <a href="#" class="btn btn-primary btn-sm">
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
                        </div>

                        @if (!empty($uktsemester['pengajuan_cicilan']) && isset($uktsemester['pengajuan_cicilan'][0]['id']))
                            <!-- Pengajuan cicilan sudah masuk -->
                            <div class="alert alert-warning mt-2 mb-2" role="alert">
                                <i class="fas fa-info-circle"></i> Pengajuan cicilan anda sudah masuk, silahkan lanjutkan proses selanjutnya.
                            </div>
                        @else
                            <!-- Belum ada pengajuan cicilan -->
                            <div class="alert alert-warning mt-2 mb-2" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> Silahkan pilih untuk metode pembayaran anda.
                            </div>
                        @endif
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
                    <div class="table-responsive ">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th>ID Pembayaran</th>
                                    <th>Tagihan</th>
                                    <th>Terbayar</th>
                                    <th>Belum Dibayar</th>
                                    <th>Status Verifikasi</th>
                                    <th>Dibayar Melalui</th>
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
            </div>
        </div>
                <!-- Upload Bukti Pembayaran Section -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Upload Bukti Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <!-- Tombol Upload -->
                        <div class="mb-3">
                            {{-- <a href="{{ route('upload-bukti-pembayaran', ['id' => $uktSemester['id']]) }}" class="btn btn-primary" style="min-width: 220px;"> --}}
                            <a href="{{ route('upload-bukti-pembayaran', ['id' => $uktSemester['id']]) }}" class="btn btn-primary" style="min-width: 220px;">
                                <i class="fas fa-plus"></i> Tambah Bukti Pembayaran
                            </a>
                        </div>

                        <!-- Tabel Upload Bukti Pembayaran -->
                        <div class="table-responsive">
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
                                    @if (!empty($uktSemester['pembayaran']) && count($uktSemester['pembayaran']) > 0)
                                        @php $adaBukti = false; @endphp

                                        @foreach ($uktSemester['pembayaran'] as $index => $pembayaran)
                                            @if (!empty($pembayaran['detail_pembayaran']) && count($pembayaran['detail_pembayaran']) > 0)
                                                @php
                                                    $detail = $pembayaran['detail_pembayaran'][0];
                                                    $adaBukti = true;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $uktSemester['enrollment']['mahasiswa']['nama_lengkap'] }}</td>
                                                    <td>BANK {{ $detail['metode_pembayaran'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($detail['tanggal_pembayaran'])->translatedFormat('d F Y') }}</td>
                                                    <td>Rp{{ number_format($detail['nominal'], 0, ',', '.') }}</td>
                                                    <td>{{ $detail['catatan'] ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{-- <a href="{{ asset('storage/' . $detail['bukti_pembayaran_path']) }}" download class="btn btn-sm btn-primary"> --}}
                                                             <a href="{{ asset('storage/' . $detail['bukti_pembayaran_path']) }}" class="btn btn-sm btn-primary" title="Lihat Bukti" target="_blank">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-success" title="Edit">
                                                        {{-- <a href="{{ route('edit-bukti-pembayaran', ['id' => $detail['id']]) }}" class="btn btn-sm btn-success" title="Edit"> --}}
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus bukti pembayaran ini?')">
                                                        {{-- <a href="{{ route('hapus-bukti-pembayaran', ['id' => $detail['id']]) }}" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus bukti pembayaran ini?')"> --}}
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @if (!$adaBukti)
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada bukti pembayaran</td>
                                            </tr>
                                        @endif
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