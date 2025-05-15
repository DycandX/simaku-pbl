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
<div class="container-fluid">
    <div class="detail-container">
        <div class="detail-header">
            <h5 class="mb-0">Informasi Pengajuan Cicilan</h5>
        </div>
        <div class="detail-body">
            <table class="detail-table">
                <tr>
                    <th>Nama</th>
                    <td>{{ $mahasiswa['nama'] ?? 'Siti Nurhaliza' }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $mahasiswa['nim'] ?? '4.33.23.4.10' }}</td>
                </tr>
                <tr>
                    <th>Jurusan</th>
                    <td>{{ $mahasiswa['jurusan'] ?? 'Administrasi Bisnis' }}</td>
                </tr>
                <tr>
                    <th>Program Studi</th>
                    <td>{{ $mahasiswa['prodi'] ?? 'D4 - Manajemen Bisnis Internasional' }}</td>
                </tr>
                <tr>
                    <th>Semester</th>
                    <td>{{ $mahasiswa['semester'] ?? '2023/2024 - Genap' }}</td>
                </tr>
                <tr>
                    <th>Jumlah Cicilan</th>
                    <td>{{ $pengajuan['jumlah_cicilan'] ?? '4x' }}</td>
                </tr>
                <tr>
                    <th>Nominal per Cicilan</th>
                    <td>Rp {{ number_format($pengajuan['nominal_per_cicilan'] ?? 2500000, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <td>{{ $pengajuan['tanggal_pengajuan'] ?? '15 Januari 2024' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if(($pengajuan['status'] ?? 'Diverifikasi') == 'Diverifikasi')
                            <span class="badge-diverifikasi">Diverifikasi</span>
                        @elseif(($pengajuan['status'] ?? '') == 'Belum Diverifikasi')
                            <span class="badge-belum-diverifikasi">Belum Diverifikasi</span>
                        @elseif(($pengajuan['status'] ?? '') == 'Ditolak')
                            <span class="badge-ditolak">Ditolak</span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="action-buttons">
                <a href="#" class="btn btn-view" target="_blank">
                    <i class="fas fa-eye action-icon"></i> Lihat File
                </a>

                @if(($pengajuan['status'] ?? '') != 'Diverifikasi' && ($pengajuan['status'] ?? '') != 'Ditolak')
                <button class="btn btn-approve" id="approveBtn" data-id="{{ $id ?? 1 }}">
                    <i class="fas fa-check-circle action-icon"></i> Verifikasi
                </button>

                <button class="btn btn-reject" id="rejectBtn" data-id="{{ $id ?? 1 }}">
                    <i class="fas fa-times-circle action-icon"></i> Tolak
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Konfirmasi Modal untuk Verifikasi -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Konfirmasi Verifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memverifikasi pengajuan cicilan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmApprove">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Konfirmasi Modal untuk Penolakan -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejectReason">Alasan Penolakan:</label>
                        <textarea class="form-control" id="rejectReason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmReject">Tolak</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Event handler untuk tombol verifikasi
    $('#approveBtn').click(function() {
        $('#approveModal').modal('show');
    });

    // Event handler untuk tombol tolak
    $('#rejectBtn').click(function() {
        $('#rejectModal').modal('show');
    });

    // Event handler untuk konfirmasi verifikasi
    $('#confirmApprove').click(function() {
        const id = $('#approveBtn').data('id');

        // AJAX request untuk verifikasi
        $.ajax({
            url: '/staff/pengajuan-cicilan/' + id + '/approve',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#approveModal').modal('hide');

                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memverifikasi pengajuan cicilan!',
                });
            }
        });
    });

    // Event handler untuk konfirmasi penolakan
    $('#confirmReject').click(function() {
        const id = $('#rejectBtn').data('id');
        const reason = $('#rejectReason').val();

        if (!reason) {
            alert('Alasan penolakan harus diisi!');
            return;
        }

        // AJAX request untuk penolakan
        $.ajax({
            url: '/staff/pengajuan-cicilan/' + id + '/reject',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    $('#rejectModal').modal('hide');

                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan error
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menolak pengajuan cicilan!',
                });
            }
        });
    });
});
</script>
@endsection