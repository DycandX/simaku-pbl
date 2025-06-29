@extends('layouts.staff-app')

@section('title', 'Detail Pengajuan Cicilan')

@section('header', 'Detail Pengajuan Cicilan')

@section('styles')
<style>
    .detail-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .detail-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
        font-weight: 600;
    }

    .detail-body {
        padding: 20px;
    }

    .detail-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .detail-table th {
        width: 200px;
        text-align: left;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .detail-table td {
        padding: 10px 15px;
        border: 1px solid #dee2e6;
    }

    .badge-diverifikasi {
        background-color: #d1ecf1;
        color: #0c5460;
        padding: 6px 15px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-belum-diverifikasi {
        background-color: #fff3cd;
        color: #856404;
        padding: 6px 15px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-ditolak {
        background-color: #f8d7da;
        color: #721c24;
        padding: 6px 15px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-view {
        background-color: #17a2b8;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }

    .btn-view:hover {
        background-color: #138496;
        color: white;
    }

    .btn-approve {
        background-color: #28a745;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }

    .btn-approve:hover {
        background-color: #218838;
        color: white;
    }

    .btn-reject {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }

    .btn-reject:hover {
        background-color: #c82333;
        color: white;
    }

    .btn-back {
        background-color: #6c757d;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
    }

    .action-icon {
        margin-right: 6px;
    }
</style>
@endsection

@section('header_button')
<a href="{{ route('staff.pengajuan-cicilan') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left mr-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Nama Lengkap :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailPengajuanCicilan['nama'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>NIM :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailPengajuanCicilan['nim']  ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Program Studi :</strong></p>
                            </div>
                            <div class="col-8">
                                <p class="text-dark mb-0">{{ $detailPengajuanCicilan['prodi']  ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <p class="mb-0"><strong>Semester</strong></p>
                            </div>
                            <div class="col-8">
                               <p class="text-dark mb-0">{{ $detailPengajuanCicilan['semester']  ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail Pengajuan Cicilan --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Angsuran Diajukan</th>
                                <th>Angsuran Disetujui</th>
                                <th>Penanggung Jawab</th>
                                <th>Status</th>
                                <th>File Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $detailPengajuanCicilan['AngsuranDiajukan'] }}</td>
                                <td>{{ $detailPengajuanCicilan['AngsuranDisetujui'] }}</td>
                                <td>{{ $detailPengajuanCicilan['diverifikasi'] }}</td>
                                <td>
                                    @php
                                        $status = strtolower($detailPengajuanCicilan['status']);
                                        $badgeClass = match ($status) {
                                            'approved' => 'badge bg-success',
                                            'rejected' => 'badge bg-danger',
                                            'pending' => 'badge bg-primary',
                                            default => 'badge bg-secondary',
                                        };
                                    @endphp

                                    <span class="{{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($detailPengajuanCicilan['file_path'])
                                        <a href="{{ asset('storage/' . $detailPengajuanCicilan['file_path']) }}" class="btn btn-sm btn-primary" title="Lihat File" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $detailPengajuanCicilan['file_path']) }}" class="btn btn-sm btn-success" title="Download File" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        Tidak ada file
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('pengajuan-cicilan.update-status', $detailPengajuanCicilan['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengajuan ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('pengajuan-cicilan.update-status', $detailPengajuanCicilan['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pengajuan ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <a href="{{ route('pengajuan-cicilan.form-update-cicilan', ['id' => $detailPengajuanCicilan['id']]) }}" class="btn btn-primary mr-3">
                        <i class="fas fa-upload mr-1"></i> Buat Hasil Pengajuan Cicilan
                    </a>
                    <a href="{{ route('staff.pengajuan-cicilan') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Buat Tagihan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
</script>
@endsection