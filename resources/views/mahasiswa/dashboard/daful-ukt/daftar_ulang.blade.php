@extends('layouts.app')

@section('title', 'Daftar Ulang UKT')

@section('header', 'Dashboard Riwayat Daftar Ulang Mahasiswa')

@section('styles')
<style>
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 75%;
        font-weight: 700;
        border-radius: 0.25rem;
    }
    .status-sudah {
        background-color: #e6f4ea;
        color: #1e7e34;
    }
    .status-belum {
        background-color: #ffe7e7;
        color: #dc3545;
    }
    .status-banding {
        background-color: #f5f5f5;
        color: #333;
    }
    .table-header {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .pagination {
        margin-bottom: 0;
    }
    .view-icon {
        color: #4e73df;
        cursor: pointer;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-header">
                                <th>No</th>
                                <th>No Tagihan</th>
                                {{-- <th>Mahasiswa</th> --}}
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Semester</th>
                                <th>Total</th>
                                <th>Bank Tujuan</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <th>Keterangan Tagihan</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataTransaksi as $data)
                            <tr>
                                <td>{{ $data['no'] }}</td>
                                <td>{{ $data['no_tagihan'] }}</td>
                                {{-- <td>{{ $data['nama_mahasiswa'] }}</td> --}}
                                <td>{{ $data['tanggal_terbit'] }}</td>
                                <td>{{ $data['jatuh_tempo'] }}</td>
                                <td>{{ $data['semester'] }}</td>
                                <td>{{ $data['total'] }}</td>
                                <td>{{ $data['bank'] }}</td>
                                <td>
                                    <span class="badge {{ $data['status_tagihan'] === 'publish' ? 'status-sudah' : 'status-belum' }}">
                                        {{ ucfirst($data['status_tagihan']) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $data['status_payment'] === 'Sudah Dibayar' ? 'status-sudah' : 'status-belum' }}">
                                        {{ $data['status_payment'] }}
                                    </span>
                                </td>
                                <td>{{ $data['keterangan'] }}</td>
                                <td>
                                    @if($data['bukti'])
                                        <a href="{{ asset($data['bukti']) }}" target="_blank">
                                            <i class="far fa-eye view-icon"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">Data tidak tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">Showing 1-3 of 2</div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-header">
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Semester</th>
                                <th>Daftar Ulang</th>
                                <th>Tanggal Daftar Ulang</th>
                                <th>Status</th>
                                <th>Urutan Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataDaftarUlang as $item)
                            <tr>
                                <td>{{ str_pad($item['no'], 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $item['kelas'] }}</td>
                                <td>{{ $item['semester'] }}</td>
                                <td>{{ $item['daftar_ulang'] }}</td>
                                <td>{{ $item['tanggal_daftar_ulang'] }}</td>
                                <td>{{ ucfirst ($item['status']) }}</td>
                                <td>{{ $item['urutan_semester'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data daftar ulang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handler untuk tombol view bukti pembayaran
    $('.view-icon').on('click', function() {
        // Implementasi untuk menampilkan bukti pembayaran
        alert('Menampilkan bukti pembayaran...');
    });
});
</script>
@endsection