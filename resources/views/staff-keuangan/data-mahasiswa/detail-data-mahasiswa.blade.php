@extends('layouts.staff-app')

@section('title', 'Detail Mahasiswa - SIMAKU')

@section('header', 'Detail Mahasiswa')

@section('content')
<div class="row">
    <!-- Detail Data Mahasiswa -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Data Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $studentData['mahasiswa']['nama_lengkap'] }}</p>
                        <p><strong>NIM:</strong> {{ $studentData['mahasiswa']['nim'] }}</p>
                        <p><strong>Jurusan:</strong> {{ $studentData['kelas']['faculty_name'] }}</p>
                        <p><strong>Program Studi:</strong> {{ $studentData['kelas']['program_name'] }}</p>
                        <p><strong>Kelas:</strong> {{ $studentData['kelas']['nama_kelas'] }}</p>
                        <p><strong>Golongan UKT:</strong> {{ $studentData['ukt_semester']['id_golongan_ukt'] }}</p>
                        <p><strong>Status Mahasiswa:</strong> 
                            <span class="badge badge-pill badge-success">Aktif</span>
                        </p>
                    </div>

                    <div class="col-md-6 text-right">
                        <a href="{{ route('staff-keuangan.data-mahasiswa.data-banding-ukt') }}" class="btn btn-view">
                            <i class="fas fa-eye"></i> Banding UKT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Daftar Ulang Mahasiswa -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Daftar Ulang Mahasiswa</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Tagihan</th>
                                <th>Mahasiswa</th>
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Semester</th>
                                <th>Total</th>
                                <th>Bank Tujuan</th>
                                <th>Status</th>
                                <th>Status Payment</th>
                                <th>Keterangan Tagihan</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentData as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment['nomor_tagihan'] }}</td>
                                <td>{{ $studentData['mahasiswa']['nama_lengkap'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['tanggal_jatuh_tempo'])->format('d M Y') }}</td>
                                <td>{{ $payment['semester'] }}</td>
                                <td>Rp {{ number_format($payment['total_tagihan'], 0, ',', '.') }}</td>
                                <td>{{ $payment['keterangan'] }}</td>
                                <td><span class="badge {{ $payment['status'] == 'belum lunas' ? 'badge-warning' : 'badge-success' }}">{{ ucfirst($payment['status']) }}</span></td>
                                <td><span class="badge {{ $payment['total_terbayar'] == 0 ? 'badge-danger' : 'badge-info' }}">Sudah Dibayar</span></td>
                                <td>-</td>
                                <td><a href="#" class="btn btn-view"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
