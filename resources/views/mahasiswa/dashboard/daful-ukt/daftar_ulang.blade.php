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
        <div class="card shadow-sm">
            <div class="card-header ">
                <h3 class="card-title mb-0">Detail Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No Tagihan</th>
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Semester</th>
                                <th>Total</th>
                                <th>Bank Tujuan</th>
                                <th>Status Tagihan</th>
                                <th>Keterangan</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataTransaksi as $data)
                            <tr>
                                <td>{{ $data['no'] }}</td>
                                <td>{{ $data['no_tagihan'] }}</td>
                                <td>{{ $data['tanggal_terbit'] }}</td>
                                <td>{{ $data['jatuh_tempo'] }}</td>
                                <td>{{ $data['semester'] }}</td>
                                <td>{{ $data['total'] }}</td>
                                <td class="text-uppercase">{{ $data['bank'] }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $data['status_tagihan'] === 'publish' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($data['status_tagihan']) }}
                                    </span>
                                </td>
                                <td>{{ $data['keterangan'] }}</td>
                                <td>
                                    @if($data['bukti'] !== '-' && $data['bukti'] !== null)
                                            <a href="{{ asset('storage/' . $data['bukti']) }}" class="btn btn-sm btn-primary" title="Lihat Bukti" target="_blank">
                                                <i class="far fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Data tidak tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">Menampilkan {{ $dataTransaksi->count() }} data</div>
                    {{-- Optional Pagination --}}
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
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

{{-- Riwayat Daftar Ulang --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header ">
                <h3 class="card-title mb-0">Riwayat Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light">
                            <tr>
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
                                <td>
                                    <span class="badge bg-success">{{ $item['daftar_ulang'] }}</span>
                                </td>
                                <td>{{ $item['tanggal_daftar_ulang'] }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $item['status'] === 'aktif' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ ucfirst($item['status']) }}
                                    </span>
                                </td>
                                <td>{{ $item['urutan_semester'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data daftar ulang.</td>
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