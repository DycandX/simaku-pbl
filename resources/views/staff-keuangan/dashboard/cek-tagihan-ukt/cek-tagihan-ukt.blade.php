@extends('layouts.staff-app')

@section('title', 'Cek Tagihan UKT - SIMAKU')

@section('header', 'Cek Tagihan UKT')

@section('content')
<div class="row">
    <!-- Status Cards -->
    <div class="col-md-4">
        <div class="status-card verified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-check-circle status-icon"></i>
                <h3>{{ $totalSudahLunas ?? 1000 }}</h3>
            </div>
            <p>Sudah Lunas</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="status-card unverified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-exclamation-circle status-icon"></i>
                <h3>{{ $totalBelumLunas ?? 4500 }}</h3>
            </div>
            <p>Belum Lunas</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="status-card">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-file-invoice status-icon"></i>
                <h3>{{ $totalSemuaTagihan ?? 5500 }}</h3>
            </div>
            <p>Semua Tagihan</p>
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
                        <label for="filterProdi">Program Studi</label>
                        <select class="form-control" id="filterProdi">
                            <option value="">Semua Program Studi</option>
                            <option value="D4 - Manajemen Bisnis" selected>D4 - Manajemen Bisnis</option>
                            <option value="D4 - Teknik Informatika">D4 - Teknik Informatika</option>
                            <option value="D4 - Akuntansi">D4 - Akuntansi</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterStatus">Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="Sudah Lunas">Sudah Lunas</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="searchInput">Cari</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama/NIM...">
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tagihan Table -->
    <div class="col-12">
        <div class="table-container card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tagihan UKT</h3>
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
                                <th>Total Tagihan</th>
                                <th>Total Dibayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>INV00012340B</td>
                                <td>Zirlda Syafira</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 2.600.000,00</td>
                                <td>Rp 2.600.000,00</td>
                                <td><span class="badge-diverifikasi">Sudah Lunas</span></td>
                                <td>
                                    <a href="{{ route('staff.cek-tagihan-ukt.detail', 'INV00012340B') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>INV001234501</td>
                                <td>Fadhil Ramadhan</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 3.000.000,00</td>
                                <td>Rp 3.000.000,00</td>
                                <td><span class="badge-diverifikasi">Sudah Lunas</span></td>
                                <td>
                                    <a href="{{ route('staff.cek-tagihan-ukt.detail', 'INV001234501') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>INV001234502</td>
                                <td>Aisyah Hanifah</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 3.000.000,00</td>
                                <td>Rp 2.850.000,00</td>
                                <td><span class="badge-belum-diverifikasi">Belum Lunas</span></td>
                                <td>
                                    <a href="{{ route('staff.cek-tagihan-ukt.detail', 'INV001234502') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>INV001234503</td>
                                <td>Yudha Prasetyo</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 2.700.000,00</td>
                                <td>Rp 2.700.000,00</td>
                                <td><span class="badge-diverifikasi">Sudah Lunas</span></td>
                                <td>
                                    <a href="{{ route('staff.cek-tagihan-ukt.detail', 'INV001234503') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>INV001234506</td>
                                <td>Siti Nurhaliza</td>
                                <td>2023/2024 - Genap</td>
                                <td>Rp 2.950.000,00</td>
                                <td>Rp 2.950.000,00</td>
                                <td><span class="badge-diverifikasi">Sudah Lunas</span></td>
                                <td>
                                    <a href="{{ route('staff.cek-tagihan-ukt.detail', 'INV001234506') }}" class="btn btn-view">
                                        <i class="fas fa-eye"></i> Lihat Tagihan
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
                            Menampilkan 1-5 dari 5500 data
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
        // Filter functionality can be implemented here
        $('.btn-filter').on('click', function() {
            // Handle filter logic
        });

        // Reset filter
        $('.btn-reset').on('click', function() {
            $('#filterSemester').val('');
            $('#filterProdi').val('');
            $('#filterStatus').val('');
            $('#searchInput').val('');
        });
    });
</script>
@endsection