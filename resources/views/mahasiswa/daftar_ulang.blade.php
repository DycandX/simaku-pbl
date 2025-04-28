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
    .status-bandung {
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
                                <th>Mahasiswa</th>
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
                            <tr>
                                <td>01</td>
                                <td>INV000013408</td>
                                <td>Zirlda Syafira</td>
                                <td>29 Jan 2024</td>
                                <td>2 Feb 2024</td>
                                <td>2023/ 2024 - Genap</td>
                                <td>Rp 3.600.000,00-</td>
                                <td>BNI</td>
                                <td><span class="badge status-sudah">Publish</span></td>
                                <td><span class="badge status-sudah">Sudah Dibayar</span></td>
                                <td>-</td>
                                <td><i class="far fa-eye view-icon"></i></td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>INV000014590</td>
                                <td>Zirlda Syafira</td>
                                <td>7 July 2024</td>
                                <td>9 July 2024</td>
                                <td>2024/ 2025 - Gasal</td>
                                <td>Rp 2.600.000,00-</td>
                                <td>BNI</td>
                                <td><span class="badge status-sudah">Publish</span></td>
                                <td><span class="badge status-sudah">Sudah Dibayar</span></td>
                                <td>Bandung UKT Berhasil Dhukukan</td>
                                <td><i class="far fa-eye view-icon"></i></td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>INV000016030</td>
                                <td>Zirlda Syafira</td>
                                <td>20 Jan 2025</td>
                                <td>23 Jan 2025</td>
                                <td>2024/ 2025 - Genap</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="badge status-belum">Belum Dibayar</span></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
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
                            <tr>
                                <td>01</td>
                                <td>MB-1A</td>
                                <td>2023/2024 - Gasal</td>
                                <td>Sudah</td>
                                <td>01 Juli 2023</td>
                                <td>Aktif</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>MB-1A</td>
                                <td>2023/2024 - Genap</td>
                                <td>Sudah</td>
                                <td>01 Feb 2024</td>
                                <td>Aktif</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>MB-2A</td>
                                <td>2024/2025 - Gasal</td>
                                <td>Sudah</td>
                                <td>15 Juli 2024</td>
                                <td>Aktif</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>MB-2A</td>
                                <td>2024/2025 - Genap</td>
                                <td>Sudah</td>
                                <td>04 Feb 2025</td>
                                <td>Aktif</td>
                                <td>4</td>
                            </tr>
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