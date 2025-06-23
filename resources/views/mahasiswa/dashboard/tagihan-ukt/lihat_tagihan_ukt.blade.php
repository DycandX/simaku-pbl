@extends('layouts.app')

@section('title', 'Dashboard - SIMAKU')

@section('header', 'Dashboard Tagihan UKT')

@section('header_button')
<a href="{{ route('golongan-ukt') }}" class="btn btn-primary">
    <i class="fas fa-eye"></i> Lihat Golongan UKT
</a>
@endsection

@section('content')
{{-- @php
    $totalSemuaTagihan = collect($uktSemester)->sum(function($item) {
        return (int) $item['jumlah_ukt'];
    });

    $totalSudahTerbayar = collect($pembayaran)->sum(function($item) {
    return $item['status'] === 'sudah lunas' ? (int) $item['nominal_tagihan'] : 0;
    });

    $totalBelumTerbayar = $totalSemuaTagihan - $totalSudahTerbayar;
@endphp --}}
@php
    $totalSemuaTagihan = collect($dataTagihan)->sum('nominal_tagihan');
    $totalSudahTerbayar = collect($dataTagihan)->sum('total_terbayar');
    $totalBelumTerbayar = $totalSemuaTagihan - $totalSudahTerbayar;
@endphp

<div class="row">
    <!-- Semua Tagihan -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Semua Tagihan</h6>
                <h3 class="mb-0">Rp {{ number_format($totalSemuaTagihan, 2, ',', '.') }}</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Belum Terbayar -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Belum Terbayar</h6>
                <h3 class="mb-0">Rp {{ number_format($totalBelumTerbayar, 2, ',', '.') }}</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Sudah Terbayar -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Sudah Terbayar</h6>
                <h3 class="mb-0">Rp {{ number_format($totalSudahTerbayar, 2, ',', '.') }}</h3>
                <span class="rounded-circle wallet-icon">
                    <i class="fas fa-wallet"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Semua Tagihan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>No Tagihan</th>
                                <th>Semester</th>
                                <th>Total Tagihan</th>
                                <th>Total Terbayar</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataTagihan as $index => $item)
                                @php
                                    $total_tagihan = $item['nominal_tagihan'] ?? 0;
                                    $total_terbayar = $item['total_terbayar'] ?? 0;
                                    $status_raw = strtolower($item['status_raw'] ?? '-');

                                    $badgeClass = match ($status_raw) {
                                        'terbayar'      => 'badge-success',
                                        'belum_bayar'   => 'badge-danger',
                                        'cancelled'     => 'badge-secondary',
                                        'over'          => 'badge-warning',
                                        default         => 'badge-info',
                                    };

                                    $statusText = $item['status'] ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ 'INV' . str_pad($item['id_ukt_semester'], 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item['periode'] }}</td>
                                    <td>Rp {{ number_format($total_tagihan, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($total_terbayar, 2, ',', '.') }}</td>
                                    <td><span class="badge badge-primary">{{ $item['jenis'] }}</span></td>
                                    <td><span class="badge {{ $badgeClass }}">{{ $statusText }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('mahasiswa-dashboard.show', ['id' => $item['id_ukt_semester']]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Tagihan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada tagihan.</td>
                                </tr>
                            @endforelse
          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
