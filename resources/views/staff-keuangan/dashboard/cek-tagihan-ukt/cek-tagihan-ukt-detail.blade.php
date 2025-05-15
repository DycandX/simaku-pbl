@extends('layouts.staff-app')

@section('title', 'Detail Tagihan UKT - SIMAKU')

@section('header', 'Detail Tagihan UKT')

@section('header_button')
<a href="{{ route('staff.cek-tagihan-ukt') }}" class="btn btn-primary">
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
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nama :</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">Zirlda Syafira</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NIM :</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">4.33.23.4.20</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Jurusan :</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">Administrasi Bisnis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Program Studi :</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">D4 - Manajemen Bisnis Internasional</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status :</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Tagihan</th>
                                <th>Semester</th>
                                <th>Tanggal Terbit</th>
                                <th>Jatuh Tempo</th>
                                <th>Total Tagihan</th>
                                <th>Total Dibayar</th>
                                <th>Status</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INV001234506</td>
                                <td>2023/2024 - Genap</td>
                                <td>29-Jan-2024</td>
                                <td>20-Feb-2024</td>
                                <td>Rp 2.600.000,00</td>
                                <td>Rp 2.600.000,00</td>
                                <td><span class="badge-diverifikasi">Sudah Lunas</span></td>
                                <td><span class="badge bg-info">KONTAJ</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h5>Riwayat Pembayaran</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>10-Feb-2024</td>
                                    <td>Rp 1.300.000,00</td>
                                    <td>Transfer Bank</td>
                                    <td><span class="badge-diverifikasi">Terverifikasi</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>18-Feb-2024</td>
                                    <td>Rp 1.300.000,00</td>
                                    <td>Transfer Bank</td>
                                    <td><span class="badge-diverifikasi">Terverifikasi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Bank:</strong> Bank Mandiri</p>
                                <p><strong>No. Rekening:</strong> 1234-5678-9012-3456</p>
                                <p><strong>Atas Nama:</strong> Universitas XYZ</p>
                                <p><small>* Harap sertakan No. Tagihan pada keterangan transfer</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title">Catatan</h5>
                            </div>
                            <div class="card-body">
                                <p>Pembayaran telah diverifikasi dan lunas.</p>
                                <p><small>Diverifikasi oleh: Staff Keuangan pada 18-Feb-2024</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection