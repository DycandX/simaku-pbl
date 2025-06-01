@extends('layouts.staff-app')

@section('title', 'Data Mahasiswa - SIMAKU')

@section('header', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Filter Section -->
        <div class="col-12 mb-4">
            <div class="filter-section">
                <div class="filter-title">Filter Mahasiswa</div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="filterAngkatan">Angkatan</label>
                            <select class="form-control" id="filterAngkatan">
                                <option value="">Semua Angkatan</option>
                                <option value="2023" selected>2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
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
                            <label for="searchInput">Cari</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Nama/NIM...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary mr-2" id="filterButton">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="resetButton">
                            <i class="fas fa-sync-alt mr-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Mahasiswa Table -->
        <div class="col-12">
            <div class="table-container card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Mahasiswa</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Angkatan</th>
                                    <th>Jurusan</th>
                                    <th>Prodi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>Zirlda Syafira</td>
                                    <td>4.33.23.5.19</td>
                                    <td>2023</td>
                                    <td>Administrasi Bisnis</td>
                                    <td>D4 - Manajemen Bisnis Internasional</td>
                                    <td>
                                        <a href="{{ route('staff.keuangan.data-mahasiswa.detail', '4.33.23.5.19') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>Fadhil Ramadhan</td>
                                    <td>4.33.23.5.20</td>
                                    <td>2023</td>
                                    <td>Administrasi Bisnis</td>
                                    <td>D4 - Manajemen Bisnis Internasional</td>
                                    <td>
                                        <a href="{{ route('staff.keuangan.data-mahasiswa.detail', '4.33.23.5.20') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>Aisyah Hanifah</td>
                                    <td>4.33.23.5.21</td>
                                    <td>2023</td>
                                    <td>Administrasi Bisnis</td>
                                    <td>D4 - Manajemen Bisnis Internasional</td>
                                    <td>
                                        <a href="{{ route('staff.keuangan.data-mahasiswa.detail', '4.33.23.5.21') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04</td>
                                    <td>Yudha Prasetyo</td>
                                    <td>4.33.23.5.22</td>
                                    <td>2023</td>
                                    <td>Administrasi Bisnis</td>
                                    <td>D4 - Manajemen Bisnis Internasional</td>
                                    <td>
                                        <a href="{{ route('staff.keuangan.data-mahasiswa.detail', '4.33.23.5.22') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>Lestari Widya</td>
                                    <td>4.33.23.5.24</td>
                                    <td>2023</td>
                                    <td>Administrasi Bisnis</td>
                                    <td>D4 - Manajemen Bisnis Internasional</td>
                                    <td>
                                        <a href="{{ route('staff.keuangan.data-mahasiswa.detail', '4.33.23.5.24') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
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
                                Menampilkan 1-5 dari 7500 data
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
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality can be implemented here
        $('#filterButton').on('click', function() {
            // Implement filter logic
        });

        // Reset filter
        $('#resetButton').on('click', function() {
            $('#filterAngkatan').val('');
            $('#filterProdi').val('');
            $('#searchInput').val('');
        });
    });
</script>
@endsection