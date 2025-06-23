@extends('layouts.staff-app')

@section('title', 'Dashboard Pengajuan Cicilan')

@section('header', 'Dashboard Pengajuan Cicilan')

@section('styles')
<style>
    .filter-section {
        background-color: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .filter-title {
        font-size: 1.5rem;
        margin-bottom: 15px;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Status Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="status-card verified">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-check-circle status-icon"></i>
                    <div>
                        <h3>5</h3>
                        <p>Diverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card unverified">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-clock status-icon"></i>
                    <div>
                        <h3>2</h3>
                        <p>Belum Diverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card rejected">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-times-circle status-icon"></i>
                    <div>
                        <h3>0</h3>
                        <p>Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">    
        <div class="row align-items-center">
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label for="semesterFilter">Semester</label>
                    <select class="form-control" id="semesterFilter">
                        <option value="2023/2024 - Genap">2023/2024 - Genap</option>
                        <option value="2023/2024 - Ganjil">2023/2024 - Ganjil</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label for="statusFilter">Status</label>
                    <select class="form-control" id="statusFilter">
                        <option value="All Status">All Status</option>
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
                <button type="button" class="btn btn-primary mr-2" id="filterSemester">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <button type="button" class="btn btn-outline-secondary" id="filterStatus">
                    <i class="fas fa-sync-alt mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container card">
        <div class="card-header">
            <h5 class="mb-0">Data Pengajuan Cicilan</h5>
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
                    <!-- Dynamic rows will be populated here -->
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                Menampilkan 1-5 dari 50 data
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
    // Data pengajuan cicilan (mock data)
    const pengajuanData = [
        { nim: "4.33.23.5.19", nama: "Zirlda Syafira", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.20", nama: "Fadhil Ramadhan", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.21", nama: "Aisyah Hanifah", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.22", nama: "Yudha Prasetyo", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" },
        { nim: "4.33.23.5.23", nama: "Lestari Widya", semester: "2023 - Genap", jurusan: "Administrasi Bisnis", prodi: "D4 - Manajemen Bisnis Internasional", status: "Diverifikasi" }
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

        tableBody.innerHTML = ""; // Clear existing rows

        // Iterate through filtered data and populate table
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
                <td><a href="{{ route('staff.pengajuan-cicilan.detail', '') }}/${item.nim}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Lihat Pengajuan</a></td>
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
                return '<span class="badge badge-success">Diverifikasi</span>';
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
        document.querySelector('.pagination-info').textContent = `Menampilkan ${startIndex}-${endIndex} dari ${data.length} data`;
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

        // Loop through total pages
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

    // Filter functions
    function applyFilters() {
        const semesterValue = document.getElementById('semesterFilter').value;
        const statusValue = document.getElementById('statusFilter').value;
        const searchValue = document.getElementById('searchInput').value.toLowerCase();

        filteredData = pengajuanData.filter(item => {
            const matchesSemester = semesterValue === '' || item.semester.includes(semesterValue.split(' - ')[0]);
            const matchesStatus = statusValue === 'All Status' || item.status === statusValue;
            const matchesSearch = searchValue === '' ||
                item.nama.toLowerCase().includes(searchValue) ||
                item.nim.includes(searchValue);

            return matchesSemester && matchesStatus && matchesSearch;
        });

        currentPage = 1;
        renderTable(filteredData, currentPage);
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
});
</script>
@endsection