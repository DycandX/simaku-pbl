@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT - SIMAKU')

@section('header', 'STAFF - PEMBAYARAN UKT')

@section('content')
<div class="row">
    <!-- Status Cards -->
    <div class="col-md-3">
        <div class="status-card bg-info text-white">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-money-bill-wave status-icon"></i>
                <h3>Rp 700.000.000</h3>
            </div>
            <p>Total Pembayaran</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="status-card verified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-check-circle status-icon"></i>
                <h3>1000</h3>
            </div>
            <p>Diverifikasi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="status-card unverified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-clock status-icon"></i>
                <h3>200</h3>
            </div>
            <p>Belum Diverifikasi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="status-card rejected">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-times-circle status-icon"></i>
                <h3>2</h3>
            </div>
            <p>Ditolak</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="col-12 mb-4">
        <div class="filter-section">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterSemester">Semester</label>
                        <select class="form-control" id="filterSemester">
                            <option value="">Semua Semester</option>
                            <option value="2023/2024 - Genap" selected>2023/2024 - Genap</option>
                            <option value="2023/2024 - Ganjil">2023/2024 - Ganjil</option>
                            <option value="2022/2023 - Genap">2022/2023 - Genap</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterStatus">Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Diverifikasi">Diverifikasi</option>
                            <option value="Belum Diverifikasi">Belum Diverifikasi</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="searchInput">Cari</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama/NIM...">
                    </div>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="col-12">
        <div class="table-container card">
            <div class="card-header">
                <h3 class="card-title">Data Pembayaran UKT</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Tagihan</th>
                                <th>Mahasiswa</th>
                                <th>Semester</th>
                                <th>Tanggal</th>
                                <th>Jumlah Dibayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>INV000013408</td>
                                <td>Zirilda Syafira</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>Rp 2.600.000,00</td>
                                <td><span class="badge badge-success">Diverifikasi</span></td>
                                <td>
                                    <a href="{{ route('staff.pembayaran-ukt.detail', 'INV000013408') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Pembayaran
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>INV001234501</td>
                                <td>Fadhil Ramadhan</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>Rp 3.000.000,00</td>
                                <td><span class="badge badge-success">Diverifikasi</span></td>
                                <td>
                                    <a href="{{ route('staff.pembayaran-ukt.detail', 'INV001234501') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Pembayaran
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>INV001234502</td>
                                <td>Aisyah Hanifah</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>Rp 2.850.000,00</td>
                                <td><span class="badge badge-danger">Ditolak</span></td>
                                <td>
                                    <a href="{{ route('staff.pembayaran-ukt.detail', 'INV001234502') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Pembayaran
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>INV001234503</td>
                                <td>Yudha Prasetyo</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>Rp 2.700.000,00</td>
                                <td><span class="badge badge-warning">Belum Diverifikasi</span></td>
                                <td>
                                    <a href="{{ route('staff.pembayaran-ukt.detail', 'INV001234503') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Pembayaran
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>INV001234506</td>
                                <td>Siti Nurhaliza</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>Rp 2.950.000,00</td>
                                <td><span class="badge badge-success">Diverifikasi</span></td>
                                <td>
                                    <a href="{{ route('staff.pembayaran-ukt.detail', 'INV001234506') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Pembayaran
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="pagination-info">
                            Menampilkan 1-5 dari 1202 data
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Sebelumnya</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Selanjutnya</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality
        $('.btn-primary').on('click', function() {
            // Implementasi filter
        });

        // Reset filter
        $('.btn-outline-secondary').on('click', function() {
            $('#filterSemester').val('');
            $('#filterStatus').val('');
            $('#searchInput').val('');
        });
    });
</script>
@endsection