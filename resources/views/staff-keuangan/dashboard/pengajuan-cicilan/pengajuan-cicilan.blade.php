@extends('layouts.staff-app')

@section('title', 'Dashboard Pengajuan Cicilan')

@section('header', 'Dashboard Pengajuan Cicilan')

@section('styles')
<style>
    .status-card {
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .status-card.verified {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
    }

    .status-card.unverified {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
    }

    .status-card.rejected {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
    }

    .status-card h3 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .status-card p {
        margin: 0;
        font-size: 0.9rem;
    }

    .status-icon {
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .filter-section {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }

    .table-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        overflow: hidden;
    }

    .table-container .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
        font-weight: 600;
    }

    .badge-diverifikasi {
        background-color: #d1f5ee;
        color: #34a793;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        display: block;
        width: 100%;
        text-align: center;
    }

    .btn-view {
        background-color: #4e7eff;
        border: none;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        display: block;
        width: 100%;
        text-align: center;
    }

    .btn-view:hover {
        background-color: #3a6cfa;
        color: white;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-2">
                <select class="form-control" id="semesterFilter">
                    <option value="2023/2024 - Genap">2023/2024 - Genap</option>
                    <option value="2023/2024 - Ganjil">2023/2024 - Ganjil</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary" id="filterSemester">Filter</button>
            </div>
            <div class="col-md-2">
                <select class="form-control" id="statusFilter">
                    <option value="All Status">All Status</option>
                    <option value="Diverifikasi">Diverifikasi</option>
                    <option value="Belum Diverifikasi">Belum Diverifikasi</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" id="jurusanFilter">
                    <option value="D4 - Manajemen Bisnis">D4 - Manajemen Bisnis</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary" id="filterStatus">Filter</button>
            </div>
            <div class="col-md-3 ml-auto">
                <input type="text" class="form-control" id="searchInput" placeholder="Search..." style="width: 100%;">
            </div>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="status-card verified">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle status-icon text-success"></i>
                    <div>
                        <h3 class="text-success">40</h3>
                        <p class="text-success">Diverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card unverified">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-clock status-icon text-warning"></i>
                    <div>
                        <h3 class="text-warning">10</h3>
                        <p class="text-warning">Belum Diverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card rejected">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-times-circle status-icon text-danger"></i>
                    <div>
                        <h3 class="text-danger">0</h3>
                        <p class="text-danger">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        <div class="card-header">
            <h5 class="mb-0">Semua Pengajuan</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Mahasiswa</th>
                        <th>Semester</th>
                        <th>Jurusan</th>
                        <th>Prodi</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataPengajuan">
                    <tr>
                        <td>01</td>
                        <td>4.33.23.5.19</td>
                        <td>Zirlda Syafira</td>
                        <td>2023 - Genap</td>
                        <td>Administrasi Bisnis</td>
                        <td>D4 - Manajemen Bisnis Internasional</td>
                        <td><span class="badge-diverifikasi">Diverifikasi</span></td>
                        <td><a href="{{ route('staff.pengajuan-cicilan.detail', '4.33.23.5.19') }}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>4.33.23.5.20</td>
                        <td>Fadhil Ramadhan</td>
                        <td>2023 - Genap</td>
                        <td>Administrasi Bisnis</td>
                        <td>D4 - Manajemen Bisnis Internasional</td>
                        <td><span class="badge-diverifikasi">Diverifikasi</span></td>
                        <td><a href="{{ route('staff.pengajuan-cicilan.detail', '4.33.23.5.20') }}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>4.33.23.5.21</td>
                        <td>Aisyah Hanifah</td>
                        <td>2023 - Genap</td>
                        <td>Administrasi Bisnis</td>
                        <td>D4 - Manajemen Bisnis Internasional</td>
                        <td><span class="badge-diverifikasi">Diverifikasi</span></td>
                        <td><a href="{{ route('staff.pengajuan-cicilan.detail', '4.33.23.5.21') }}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>4.33.23.5.22</td>
                        <td>Yudha Prasetyo</td>
                        <td>2023 - Genap</td>
                        <td>Administrasi Bisnis</td>
                        <td>D4 - Manajemen Bisnis Internasional</td>
                        <td><span class="badge-diverifikasi">Diverifikasi</span></td>
                        <td><a href="{{ route('staff.pengajuan-cicilan.detail', '4.33.23.5.22') }}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>4.33.23.5.23</td>
                        <td>Lestari Widya</td>
                        <td>2023 - Genap</td>
                        <td>Administrasi Bisnis</td>
                        <td>D4 - Manajemen Bisnis Internasional</td>
                        <td><span class="badge-diverifikasi">Diverifikasi</span></td>
                        <td><a href="{{ route('staff.pengajuan-cicilan.detail', '4.33.23.5.23') }}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                Showing 1-5 of 50
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Data pengajuan cicilan
    const pengajuanData = [
        { nim: "4.33.23.5.19", nama: "Zirlda Syafira", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.20", nama: "Fadhil Ramadhan", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.21", nama: "Aisyah Hanifah", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.22", nama: "Yudha Prasetyo", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.23", nama: "Lestari Widya", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.24", nama: "Ahmad Fauzi", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Belum Diverifikasi" },
        { nim: "4.33.23.5.25", nama: "Siti Nurhaliza", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Belum Diverifikasi" }
    ];

    let filteredData = pengajuanData;
    let currentPage = 1;
    const itemsPerPage = 5;

    // Render table function
    function renderTable(data, page = 1) {
        const tableBody = document.getElementById("dataPengajuan");
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = data.slice(startIndex, endIndex);

        tableBody.innerHTML = "";

        pageData.forEach((item, index) => {
            const actualIndex = startIndex + index + 1;
            const statusBadge = getStatusBadge(item.status);

            const row = `<tr>
                <td>${actualIndex.toString().padStart(2, '0')}</td>
                <td>${item.nim}</td>
                <td>${item.nama}</td>
                <td>${item.semester}</td>
                <td>${item.jurusan}</td>
                <td>${item.prodi}</td>
                <td>${statusBadge}</td>
                <td><a href="{{ route('staff.pengajuan-cicilan.detail', '') }}/${item.nim}" class="btn btn-view"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
            </tr>`;
            tableBody.insertAdjacentHTML("beforeend", row);
        });

        updatePaginationInfo(data, page);
        updatePagination(data, page);
    }

    // Get status badge function
    function getStatusBadge(status) {
        switch(status) {
            case 'Diverifikasi':
                return '<span class="badge-diverifikasi">Diverifikasi</span>';
            case 'Belum Diverifikasi':
                return '<span class="badge badge-warning">Belum Diverifikasi</span>';
            case 'Ditolak':
                return '<span class="badge badge-danger">Ditolak</span>';
            default:
                return '<span class="badge badge-secondary">-</span>';
        }
    }

    // Update pagination info
    function updatePaginationInfo(data, page) {
        const startIndex = (page - 1) * itemsPerPage + 1;
        const endIndex = Math.min(page * itemsPerPage, data.length);
        document.querySelector('.pagination-info').textContent = `Showing ${startIndex}-${endIndex} of ${data.length}`;
    }

    // Update pagination buttons
    function updatePagination(data, page) {
        const totalPages = Math.ceil(data.length / itemsPerPage);
        const pagination = document.querySelector('.pagination');

        pagination.innerHTML = `
            <li class="page-item ${page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" aria-label="Previous" data-page="${page - 1}">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        pagination.innerHTML += `
            <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" aria-label="Next" data-page="${page + 1}">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;
    }

    // Update status summary
    function updateStatusSummary(data) {
        const verified = data.filter(item => item.status === 'Diverifikasi').length;
        const unverified = data.filter(item => item.status === 'Belum Diverifikasi').length;
        const rejected = data.filter(item => item.status === 'Ditolak').length;

        document.querySelector('.status-card.verified h3').textContent = verified;
        document.querySelector('.status-card.unverified h3').textContent = unverified;
        document.querySelector('.status-card.rejected h3').textContent = rejected;
    }

    // Filter functions
    function applyFilters() {
        const semesterValue = document.getElementById('semesterFilter').value;
        const statusValue = document.getElementById('statusFilter').value;
        const searchValue = document.getElementById('searchInput').value.toLowerCase();

        filteredData = pengajuanData.filter(item => {
            const matchesSemester = semesterValue === 'All' || item.semester.includes(semesterValue.split(' - ')[0]);
            const matchesStatus = statusValue === 'All Status' || item.status === statusValue;
            const matchesSearch = searchValue === '' ||
                item.nama.toLowerCase().includes(searchValue) ||
                item.nim.includes(searchValue);

            return matchesSemester && matchesStatus && matchesSearch;
        });

        currentPage = 1;
        renderTable(filteredData, currentPage);
        updateStatusSummary(filteredData);
    }

    // Event listeners
    document.getElementById('filterSemester').addEventListener('click', applyFilters);
    document.getElementById('filterStatus').addEventListener('click', applyFilters);
    document.getElementById('searchInput').addEventListener('input', applyFilters);

    // Pagination event listeners
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('page-link') && !e.target.closest('.disabled')) {
            e.preventDefault();
            const page = parseInt(e.target.dataset.page);
            if (page && page !== currentPage) {
                currentPage = page;
                renderTable(filteredData, currentPage);
            }
        }
    });

    // Initial render
    renderTable(filteredData, currentPage);
    updateStatusSummary(filteredData);
});
</script>
@endsection