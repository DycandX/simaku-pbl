@extends('layouts.app')

@section('title', 'Beasiswa - SIMAKU')

@section('header', 'Beasiswa Pendidikan')

{{-- @section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Detail Beasiswa Mahasiswa -->
                <h5 class="mb-4">Detail Beasiswa Mahasiswa</h5>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $beasiswa[0]['beasiswa']['nama_beasiswa'] ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ strtoupper($beasiswa[0]['beasiswa']['status'] ?? '-') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Tipe Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ ucwords($beasiswa[0]['beasiswa']['jenis'] ?? '-') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Periode Mulai:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($beasiswa[0]['tanggal_mulai'])->translatedFormat('l d F Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Periode Selesai:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($beasiswa[0]['tanggal_selesai'])->translatedFormat('l d F Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Keterangan</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ ucwords($beasiswa[0]['beasiswa']['deskripsi'] ?? '-' ) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penerimaan Dana -->
                <h5 class="mb-4">Detail Penerimaan Dana</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Periode Cair</th>
                                <th>Jumlah</th>
                                <th>Tanggal Cair</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($beasiswa as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item['keterangan'])->translatedFormat('d F Y') }}</td>
                                    <td>Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item['tanggal_mulai'])->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($item['status'] === 'aktif')
                                            <span class="badge badge-success">Sudah Cair</span>
                                        @else
                                            <span class="badge badge-secondary">Belum Cair</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data beasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
</div> 
@endsection --}}
@section('content')
<div class="row">
    <div class="col-12">
        @if (!empty($beasiswa) && count($beasiswa) > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Detail Beasiswa Mahasiswa -->
                <h5 class="mb-4">Detail Beasiswa Mahasiswa</h5>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $beasiswa[0]['beasiswa']['nama_beasiswa'] ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ strtoupper($beasiswa[0]['beasiswa']['status'] ?? '-') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Tipe Beasiswa :</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ ucwords($beasiswa[0]['beasiswa']['jenis'] ?? '-') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Periode Mulai:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($beasiswa[0]['tanggal_mulai'])->translatedFormat('l d F Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Periode Selesai:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($beasiswa[0]['tanggal_selesai'])->translatedFormat('l d F Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Keterangan</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ ucwords($beasiswa[0]['beasiswa']['deskripsi'] ?? '-' ) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penerimaan Dana -->
                <h5 class="mb-4">Detail Penerimaan Dana</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Periode Cair</th>
                                <th>Jumlah</th>
                                <th>Tanggal Cair</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($beasiswa as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item['keterangan'])->translatedFormat('d F Y') }}</td>
                                    <td>Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item['tanggal_mulai'])->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if ($item['status'] === 'aktif')
                                            <span class="badge badge-success">Sudah Cair</span>
                                        @else
                                            <span class="badge badge-secondary">Belum Cair</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data beasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            Data beasiswa tidak tersedia.
        </div>
        @endif
    </div>
</div>
@endsection


@section('styles')
<style>
    .card {
        background-color: #fff;
    }

    .card-body {
        padding: 2rem;
    }

    h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .row.mb-3 {
        margin-bottom: 1rem;
    }

    .row.mb-3 .col-sm-4 {
        color: #6c757d;
    }

    .row.mb-3 .col-sm-8 {
        color: #333;
        font-weight: 500;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .badge-success {
        background-color: #4fd1c5;
        color: #fff;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: 500;
    }

    .table td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .row.mb-3 .col-sm-4,
        .row.mb-3 .col-sm-8 {
            padding: 0.5rem 0;
        }
    }
</style>
@endsection