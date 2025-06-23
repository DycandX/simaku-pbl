@extends('layouts.staff-app')

@section('title', 'Cek Tagihan UKT - SIMAKU')

@section('header', 'Cek Tagihan UKT')

@section('content')
<div class="row mb-4">
    <!-- Semua Tagihan -->
    <div class="col-md-4">
        <div class="status-card bg-info text-white">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-file-invoice status-icon"></i>
                <h3>{{ $totalSemuaTagihan ?? 5500 }}</h3>
            </div>
            <p>Semua Tagihan</p>
        </div>
    </div>
    <!-- Sudah Lunas -->
    <div class="col-md-4">
        <div class="status-card verified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-check-circle status-icon"></i>
                <h3>{{ $totalSudahLunas ?? 1000 }}</h3>
            </div>
            <p>Sudah Lunas</p>
        </div>
    </div>
    <!-- Belum Lunas -->
    <div class="col-md-4">
        <div class="status-card unverified">
            <div class="d-flex justify-content-center mb-2">
                <i class="fas fa-clock status-icon"></i>
                <h3>{{ $totalBelumLunas ?? 4500 }}</h3>
            </div>
            <p>Belum Lunas</p>
        </div>
    </div>
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
                <table class="table table-hover" id="uktTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Tagihan</th>
                            <th>Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Semester</th>
                            <th>Total Tagihan</th>
                            <th>Total Dibayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($dataTagihan as $index => $ukt)
                        @php

                            $idUkt =  $ukt['id'];
                            $mahasiswa =  $ukt['enrollment']['mahasiswa']['nama_lengkap'] ?? '-';
                            $prodi = $ukt['enrollment']['program_studi']['nama_prodi'] ?? '-';
                            $semester = $ukt['periode_pembayaran']['nama_periode']; // sesuaikan field
                            $totalTagihan = number_format($ukt['jumlah_ukt'], 0, ',', '.');
                        @endphp
                        @php
                            $totalDibayar = 0;

                            if (!empty($ukt['pembayaran']) && is_array($ukt['pembayaran'])) {
                                foreach ($ukt['pembayaran'] as $pembayaran) {
                                    if (isset($pembayaran['status']) && $pembayaran['status'] === 'terbayar') {
                                        $nominal = isset($pembayaran['nominal_tagihan']) ? (float) $pembayaran['nominal_tagihan'] : 0;
                                        $totalDibayar += $nominal;
                                    }
                                }
                            }

                            // Format jadi Rupiah
                            $totalDibayarFormatted = number_format($totalDibayar, 0, ',', '.');
                        @endphp
                        @php
                            // Ambil nilai jumlah UKT
                            $jumlahUkt = isset($ukt['jumlah_ukt']) ? (float) $ukt['jumlah_ukt'] : 0;

                            // Hitung total dibayar dari status pembayaran
                            $totalDibayar = 0;

                            if (!empty($ukt['pembayaran']) && is_array($ukt['pembayaran'])) {
                                foreach ($ukt['pembayaran'] as $pembayaran) {
                                    if (isset($pembayaran['status']) && $pembayaran['status'] === 'terbayar') {
                                        $nominal = isset($pembayaran['nominal_tagihan']) ? (float) $pembayaran['nominal_tagihan'] : 0;
                                        $totalDibayar += $nominal;
                                    }
                                }
                            }

                            // Tentukan status dan warna badge
                            if ($totalDibayar >= $jumlahUkt) {
                                $statusText = 'Sudah Lunas';
                                $statusBadge = 'success'; // hijau
                            } else {
                                $statusText = 'Belum Lunas';
                                $statusBadge = 'danger'; // merah
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ 'INV' . str_pad($idUkt, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $mahasiswa ?? '-' }}</td>
                            <td>{{ $prodi }}</td>
                            <td>{{ $semester }}</td>
                            <td>Rp {{ $totalTagihan }}</td>
                            <td>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                            {{-- Tampilkan badge status --}}
                            <td>
                                <span class="badge badge-{{ $statusBadge }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <a href="{{ route('staff.cek-tagihan-ukt.detail', $idUkt) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat Tagihan
                                </a>
                            </td> 
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data tagihan ditemukan.</td>
                        </tr>
                    @endforelse
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