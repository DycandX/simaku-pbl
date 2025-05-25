@extends('layouts.staff-app')

@section('title', 'Pembayaran UKT')

@section('header', 'STAFF - PEMBAYARAN UKT')

@section('content')
<div class="container mt-5">
    <!-- Filter Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="filter-container">
            <label for="semester">Semester</label>
            <select id="semester" class="form-select" onchange="filterData()">
                <option value="">Pilih Semester</option>
                <option value="2023/2024 - Genap" {{ $semester == '2023/2024 - Genap' ? 'selected' : '' }}>2023/2024 - Genap</option>
                <option value="2024/2025 - Genap" {{ $semester == '2024/2025 - Genap' ? 'selected' : '' }}>2024/2025 - Genap</option>
                <!-- Add more options dynamically if needed -->
            </select>
        </div>
        <div class="filter-container">
            <label for="status">Status</label>
            <select id="status" class="form-select" onchange="filterData()">
                <option value="">Semua Status</option>
                <option value="sudah lunas" {{ $status == 'sudah lunas' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="belum lunas" {{ $status == 'belum lunas' ? 'selected' : '' }}>Belum Diverifikasi</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="filter-container">
            <button class="btn btn-primary" onclick="filterData()">Filter</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-3">
            <div class="summary-card">
                <p>Total Pembayaran</p>
                <h4>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Diverifikasi</p>
                <h4>{{ $diverifikasi }}</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Belum Diverifikasi</p>
                <h4>{{ $belumDiverifikasi }}</h4>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-card">
                <p>Ditolak</p>
                <h4>{{ $ditolak }}</h4>
            </div>
        </div>
    </div>

    <!-- Payment Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
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
                @foreach ($pembayaranUktData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['nomor_tagihan'] }}</td>
                    <td>{{ $data['mahasiswa']['nama_lengkap'] }}</td>
                    <td>{{ $data['semester'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($data['tanggal_jatuh_tempo'])->format('d-M-Y') }}</td>
                    <td>Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="{{ $data['status'] == 'sudah lunas' ? 'text-success' : ($data['status'] == 'belum lunas' ? 'text-warning' : 'text-danger') }}">
                        {{ ucfirst($data['status']) }}
                    </td>
                    <td>
                        <a href="{{ route('staff.pembayaran-ukt.detail', ['id' => $data['id']]) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Pembayaran
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterData() {
        const semester = document.getElementById('semester').value;
        const status = document.getElementById('status').value;
        window.location.href = `{{ url('staff/pembayaran-ukt') }}?semester=${semester}&status=${status}`;
    }
</script>

@endsection

@section('styles')
<style>
    .filter-container {
        display: inline-block;
        margin-right: 10px;
    }
    .summary-card {
        background: #fff;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .summary-card p {
        font-size: 14px;
        color: #6c757d;
    }
    .summary-card h4 {
        margin-top: 5px;
        font-weight: bold;
    }
    .table th, .table td {
        text-align: center;
    }
    .table .btn-info {
        color: white;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
@endsection
