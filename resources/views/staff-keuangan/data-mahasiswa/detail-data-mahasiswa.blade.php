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
                        <p><strong>Nama:</strong> {{ $studentData[0]['mahasiswa']['nama_lengkap'] }}</p>
                        <p><strong>NIM:</strong> {{ $studentData[0]['mahasiswa']['nim'] }}</p>
                        <p><strong>Jurusan:</strong> {{ $studentData[0]['kelas']['faculty_name'] }}</p>
                        <p><strong>Program Studi:</strong> {{ $studentData[0]['kelas']['program_name'] }}</p>
                        <p><strong>Kelas:</strong> {{ $studentData[0]['kelas']['nama_kelas'] }}</p>
                        <p><strong>Golongan UKT:</strong> {{ $studentData[0]['ukt_semester']['id_golongan_ukt'] ?? 'Tidak Tersedia' }}</p>
                        <p><strong>Status Mahasiswa:</strong> 
                            <span class="badge badge-pill badge-success">Aktif</span>
                        </p>
                        <p><strong>Metode Pembayaran:</strong> {{ $paymentData[0]['metode_pembayaran'] ?? 'Tidak Tersedia' }}</p> <!-- Metode Pembayaran -->
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
                                <th>Tahun Akademik</th>
                                <th>Tagihan</th>
                                <th>Total Terbayar</th>
                                <th>Bank Tujuan</th>
                                <th>Status Periode</th>
                                <th>Status Pembayaran</th>
                                <th>Keterangan Tagihan</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentData as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment['id'] }}</td>
                                <td>{{ $studentData[0]['mahasiswa']['nama_lengkap'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment['tanggal_jatuh_tempo'])->format('d M Y') }}</td>
                                <td>{{ $studentData[0]['tahun_akademik'] }}</td> <!-- Tahun Akademik -->
                                <td>Rp {{ number_format($payment['nominal_tagihan'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($payment['ukt_semester']['jumlah_ukt'], 0, ',', '.') }}</td>
                                <td>{{ $payment['metode_pembayaran'] }}</td> <!-- Metode Pembayaran -->
                                <td><span class="badge {{ $payment['ukt_semester']['periode_pembayaran']['status'] == 'non-aktif' ? 'badge-warning' : 'badge-success' }}">{{ ucfirst($payment['ukt_semester']['periode_pembayaran']['status']) }}</span></td>
                                <td><span class="badge {{ $payment['nominal_tagihan'] == 0 ?  'badge-danger' : 'badge-info' }}">{{ ucfirst($payment['status']) }}</span></td>
                                
                                <td>
                                    @if(isset($payment['bukti_pembayaran_path']))
                                        <a href="{{ asset($payment['bukti_pembayaran_path']) }}" class="btn btn-view" target="_blank">
                                            <i class="fas fa-eye"></i> Lihat Bukti Pembayaran
                                        </a>
                                    @else
                                        <span class="text-muted">Bukti tidak tersedia</span>
                                    @endif
                                </td>
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
