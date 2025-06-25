@extends('layouts.staff-app')

@section('title', 'Hasil Pengajuan Cicilan UKT')

@section('header', 'Hasil Pengajuan Cicilan UKT')

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
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Hasil Pengajuan Cicilan UKT</h5>
            </div>
            <div class="card-body">
                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('staff.pengajuan-cicilan.update-hasil-cicilan', $detailPengajuanCicilan['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Read Only Info -->
                    <div class="mb-3">
                        <label class="form-label">No Tagihan UKT Semester</label>
                        <input type="text" class="form-control" value="ID: {{ $data['id_ukt_semester'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Angsuran Diajukan</label>
                        <input type="text" class="form-control" value="{{ $data['jumlah_angsuran_diajukan'] }}" readonly>
                    </div>

                    <!-- Input Staff -->
                    <div class="mb-3">
                        <label class="form-label">Jumlah Angsuran Disetujui</label>
                        <input type="number" name="jumlah_angsuran_disetujui" class="form-control" value="{{ old('jumlah_angsuran_disetujui', $data['jumlah_angsuran_disetujui']) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Approval</label>
                        <textarea name="catatan_approval" class="form-control">{{ old('catatan_approval', $data['catatan_approval']) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Setujui Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
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
@endsection