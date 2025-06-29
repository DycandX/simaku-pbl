@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT - SIMAKU')

@section('header', 'Pembayaran UKT Mahasiswa')

@section('content')
<div class="row">


    {{-- <!-- Status Cards -->
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
    </div> --}}
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Total Pembayaran</h5>
                <h3>Rp</h3>
                {{-- <h3 class="mb-0 text-primary">{{ $totalSemuaTagihan }}</h3>  --}}
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
    <!-- Sudah Lunas -->
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Diverifikasi</h5>
                <h3>Rp</h3>
                {{-- <h3 class="mb-0 text-success">{{ $totalSudahLunas }}</h3>  --}}
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
    <!-- Belum Lunas -->
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Belum Diverifikasi</h5>
                <h3>Rp</h3>
                {{-- <h3 class="mb-0 text-danger">{{ $totalBelumLunas }}</h3>  --}}
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
    <!-- Belum Lunas -->
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Ditolak</h5>
                <h3>Rp</h3>
                {{-- <h3 class="mb-0 text-danger">{{ $totalBelumLunas }}</h3>  --}}
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>

    {{-- <!-- Filter Section -->
    <div class="col-12 mb-2">
        <div class="filter-section">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterSemester">Semester</label>
                        <select class="form-control" id="filterSemester">
                            <option value="">Semua Semester</option>
                            @foreach($periodePembayaran as $periode)
                                <option value="{{ $periode['nama_periode'] }}">{{ $periode['nama_periode'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterProdi">Program Studi</label>
                        <select class="form-control" id="filterProdi">
                            <option value="">Semua Program Studi</option>
                            @foreach($programStudi as $prodi)
                                <option value="{{ $prodi['nama_prodi'] }}">
                                    {{ $prodi['nama_prodi'] }}
                                </option>
                            @endforeach
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama Lengkap">
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
    </div> --}}

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
    document.addEventListener('DOMContentLoaded', function () {
        const filterSemester = document.getElementById('filterSemester');
        const filterProdi = document.getElementById('filterProdi');
        const filterStatus = document.getElementById('filterStatus');
        const searchInput = document.getElementById('searchInput');
        const filterButton = document.querySelector('.btn.btn-primary');
        const resetButton = document.querySelector('.btn.btn-outline-secondary');
        const rows = document.querySelectorAll('#uktTable tbody tr');

        function applyFilter() {
            const semesterVal = filterSemester.value.toLowerCase();
            const prodiVal = filterProdi.value.toLowerCase();
            const statusVal = filterStatus.value.toLowerCase();
            const searchVal = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const semester = row.children[4].textContent.toLowerCase();
                const prodi = row.children[3].textContent.toLowerCase();
                const status = row.children[7].textContent.toLowerCase();
                const mahasiswa = row.children[2].textContent.toLowerCase();

                const matchSemester = !semesterVal || semester.includes(semesterVal);
                const matchProdi = !prodiVal || prodi.includes(prodiVal);
                const matchStatus = !statusVal || status.includes(statusVal);
                const matchSearch = !searchVal || mahasiswa.includes(searchVal);

                if (matchSemester && matchProdi && matchStatus && matchSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function resetFilter() {
            filterSemester.value = '';
            filterProdi.value = '';
            filterStatus.value = '';
            searchInput.value = '';
            applyFilter();
        }

        filterButton.addEventListener('click', applyFilter);
        resetButton.addEventListener('click', resetFilter);
    });
</script>
@endsection