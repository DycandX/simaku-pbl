@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT - SIMAKU')

@section('header', 'Pembayaran UKT Mahasiswa')

@section('content')
<div class="row">
    {{-- Kartu Ringkasan --}}
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Total Pembayaran</h5>
                <h3 class="mb-0 text-primary">Rp {{ number_format($totalNominalTerverifikasi, 0, ',', '.') }}</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Diverifikasi</h5>
                <h3 class="mb-0 text-success">{{ $statusSummary['diverifikasi'] }}</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
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
    <div class="col-lg-3">
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

    {{-- Filter Section --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- Semester --}}
                    <div class="col-md-4 mb-3">
                        <label for="filterSemester">Semester</label>
                        <select class="form-control" id="filterSemester">
                            <option value="">Semua Semester</option>
                            @foreach($periodePembayaran as $periode)
                                <option value="{{ $periode['nama_periode'] }}">{{ $periode['nama_periode'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">
                        <label for="filterStatus">Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="pending">Belum Diverifikasi</option>
                            <option value="verified">Diverifikasi</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>

                    {{-- Nama Mahasiswa --}}
                    <div class="col-md-4 mb-3">
                        <label for="searchInput">Cari Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama Lengkap">
                    </div>

                    <div class="col-12">
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

    {{-- Tabel Pembayaran --}}
    <div class="col-12">
        <div class="table-container card">
            <div class="card-header">
                <h3 class="card-title">Data Pembayaran UKT</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="uktTable">
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
                            @forelse ($detailPembayaranData as $index => $item)
                                @php
                                    $mahasiswa = $item['pembayaran_ukt_semester']['ukt_semester']['enrollment']['mahasiswa'];
                                    $periode = $item['pembayaran_ukt_semester']['ukt_semester']['periode_pembayaran'];
                                    $tanggal = \Carbon\Carbon::parse($item['tanggal_pembayaran'])->format('d-M-Y');
                                    $status = $item['status'];
                                @endphp
                                <tr>
                                    <td>{{ $detailPembayaranData->firstItem() + $index }}</td>
                                    <td>INV{{ str_pad($item['id'], 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $mahasiswa['nama_lengkap'] }}</td>
                                    <td>{{ $periode['nama_periode'] }}</td>
                                    <td>{{ $tanggal }}</td>
                                    <td>Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                    <td data-status="{{ $status }}">
                                        @if ($status === 'verified')
                                            <span class="badge badge-success">Diverifikasi</span>
                                        @elseif ($status === 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Belum Diverifikasi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('staff.pembayaran-ukt.show', $item['id']) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Pembayaran
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data pembayaran.</td>
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
                            Menampilkan {{ $detailPembayaranData->firstItem() }}-{{ $detailPembayaranData->lastItem() }} dari {{ $detailPembayaranData->total() }} data
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $detailPembayaranData->links('pagination::bootstrap-4') }}
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
        const filterStatus = document.getElementById('filterStatus');
        const searchInput = document.getElementById('searchInput');
        const filterButton = document.querySelector('.btn.btn-primary');
        const resetButton = document.querySelector('.btn.btn-outline-secondary');
        const rows = document.querySelectorAll('#uktTable tbody tr');

        function applyFilter() {
            const semesterVal = filterSemester.value.toLowerCase();
            const statusVal = filterStatus.value.toLowerCase();
            const searchVal = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const semester = row.children[3].textContent.toLowerCase();
                const status = row.children[6].getAttribute('data-status').toLowerCase();
                const mahasiswa = row.children[2].textContent.toLowerCase();

                const matchSemester = !semesterVal || semester.includes(semesterVal);
                const matchStatus = !statusVal || status.includes(statusVal);
                const matchSearch = !searchVal || mahasiswa.includes(searchVal);

                if (matchSemester && matchStatus && matchSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function resetFilter() {
            filterSemester.value = '';
            filterStatus.value = '';
            searchInput.value = '';
            applyFilter();
        }

        filterButton.addEventListener('click', applyFilter);
        resetButton.addEventListener('click', resetFilter);
    });
</script>
@endsection
