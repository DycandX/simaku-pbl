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
    {{-- Baris Ringkasan Status --}}
    <div class="row">
        <!-- Diverifikasi -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-dark">Diverifikasi</h5>
                    <h3 class="mb-0 text-primary">{{ $statusSummary['diverifikasi'] }}</h3>
                    <span class="rounded-circle wallet-icon">
                        <i class="fas fa-wallet"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Belum Diverifikasi -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-dark">Belum Diverifikasi</h5>
                    <h3 class="mb-0 text-warning">{{ $statusSummary['belum_diverifikasi'] }}</h3>
                    <span class="rounded-circle wallet-icon">
                        <i class="fas fa-wallet"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-dark">Ditolak</h5>
                    <h3 class="mb-0 text-danger">{{ $statusSummary['ditolak'] }}</h3>
                    <span class="rounded-circle wallet-icon">
                        <i class="fas fa-wallet"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="row ">
        <div class="col-12 mb-2">
            <div class="filter-section">
                <div class="row">
                    {{-- Semester --}}
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

                    {{-- Prodi --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="filterProdi">Program Studi</label>
                            <select class="form-control" id="filterProdi">
                                <option value="">Semua Program Studi</option>
                                @foreach($programStudi as $prodi)
                                    <option value="{{ $prodi['nama_prodi'] }}">{{ $prodi['nama_prodi'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="filterStatus">Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    {{-- Search --}}
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
        </div>
    </div>

    {{-- Table Section --}}
    <div class="row">
        <div class="col-12">
            <div class="table-container card">
                <div class="card-header">
                    <h3 class="card-title">Data Pengajuan Cicilan</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="PengajuanCicilanTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Mahasiswa</th>
                                    <th>Semester</th>
                                    <th>Program Studi</th>
                                    <th>Jumlah Angsuran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuanData as $index => $item)
                                    @php
                                        $id = $item['id'];
                                        $nim = $item['enrollment']['mahasiswa']['nim'] ?? '-';
                                        $nama = $item['enrollment']['mahasiswa']['nama_lengkap'] ?? '-';
                                        $semester = $item['ukt_semester']['periode_pembayaran']['nama_periode'] ?? '-';
                                        $prodi = $item['enrollment']['program_studi']['nama_prodi'] ?? '-';
                                        $angsuran = $item['jumlah_angsuran_diajukan'] ?? 0;
                                        $status = $item['status'] ?? 'Belum Diverifikasi';

                                        $badgeClass = match($status) {
                                            'approved' => 'success',
                                            'Ditolak', 'ditolak' => 'danger',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $pengajuanData->firstItem() + $index }}</td>
                                        <td>{{ $nim }}</td>
                                        <td>{{ $nama }}</td>
                                        <td>{{ $semester }}</td>
                                        <td>{{ $prodi }}</td>
                                        <td>{{ $angsuran }}x</td>
                                        <td><span class="badge badge-{{ $badgeClass }}">{{ ucfirst($status) }}</span></td>
                                        <td>
                                            <a href="{{ route('staff.pengajuan-cicilan.detail', $id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pengajuan ditemukan.</td>
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
                                Menampilkan {{ $pengajuanData->firstItem() }} - {{ $pengajuanData->lastItem() }} dari {{ $pengajuanData->total() }} data
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="d-flex justify-content-end">
                                {{ $pengajuanData->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- Penutup .row --}}
</div> {{-- Penutup .container-fluid --}}
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK'
        });
    @endif
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterSemester = document.getElementById('filterSemester');
        const filterProdi = document.getElementById('filterProdi');
        const filterStatus = document.getElementById('filterStatus');
        const searchInput = document.getElementById('searchInput');
        const filterButton = document.querySelector('.btn.btn-primary');
        const resetButton = document.querySelector('.btn.btn-outline-secondary');
        const rows = document.querySelectorAll('#PengajuanCicilanTable tbody tr');

        function applyFilter() {
            const semesterVal = filterSemester.value.toLowerCase();
            const prodiVal = filterProdi.value.toLowerCase();
            const statusVal = filterStatus.value.toLowerCase();
            const searchVal = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const semester = row.children[3].textContent.toLowerCase();
                const prodi = row.children[4].textContent.toLowerCase();
                const status = row.children[6].textContent.toLowerCase();
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