@extends('layouts.staff-app')

@section('title', 'Buat Tagihan UKT')

@section('header', 'Form Data Tagihan UKT')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Form Header -->
                    <h5 class="mb-4">Form Data Tagihan UKT (Batch Generation)</h5>

                    <!-- Info Alert -->
                    <div class="alert alert-info" role="alert">
                        <strong>Info:</strong> Sistem akan membuat tagihan UKT untuk semua mahasiswa yang terdaftar pada tahun akademik aktif dengan periode pembayaran yang sedang aktif.
                    </div>

                    <!-- Periode Aktif Info -->
                    @if(!empty($periodeAktif))
                    <div class="alert alert-success" role="alert">
                        <strong>Periode Aktif:</strong> {{ $periodeAktif['nama_periode'] }}
                        <br><strong>Tanggal Tagihan:</strong> {{ date('d-m-Y') }} (Hari ini)
                    </div>
                    @else
                    <div class="alert alert-warning" role="alert">
                        <strong>Peringatan:</strong> Tidak ada periode pembayaran yang aktif. Hubungi administrator untuk mengaktifkan periode pembayaran.
                    </div>
                    @endif

                    <form action="{{ route('staff.detail-buatTagihanUkt.create') }}" method="POST" id="uktForm">
                        @csrf

                        <!-- Hidden fields untuk periode dan tanggal tagihan otomatis -->
                        <input type="hidden" name="periode_aktif" value="{{ $periodeAktif['nama_periode'] ?? '' }}">
                        <input type="hidden" name="tanggal_tagihan" value="{{ date('Y-m-d') }}">

                        <!-- Info readonly fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Periode Pembayaran</label>
                                <input type="text" class="form-control" value="{{ $periodeAktif['nama_periode'] ?? 'Tidak ada periode aktif' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Tagihan</label>
                                <input type="text" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <div class="form-text">Pilih tanggal jatuh tempo pembayaran (minimal besok)</div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>Buat Tagihan UKT {{ $periodeAktif['nama_periode'] ?? '' }} untuk semua mahasiswa aktif - {{ date('d-m-Y') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn" @if(empty($periodeAktif)) disabled @endif>
                                <i class="fas fa-plus"></i> Buat Tagihan (Batch)
                            </button>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div id="previewSection" class="mt-4" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-list"></i> Preview Mahasiswa yang Akan Dibuatkan Tagihan</h6>
                            </div>
                            <div class="card-body">
                                <div id="previewContent">
                                    <!-- Content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Students Info -->
                    @if(!empty($mahasiswaData))
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h6><i class="fas fa-users"></i> Mahasiswa Aktif ({{ count($mahasiswaData) }} orang)</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Program Studi</th>
                                                <th>Golongan UKT</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mahasiswaData as $index => $enrollment)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><span class="badge bg-secondary">{{ $enrollment['mahasiswa']['nim'] ?? '-' }}</span></td>
                                                <td>{{ $enrollment['mahasiswa']['nama_lengkap'] ?? '-' }}</td>
                                                <td>{{ $enrollment['program_studi']['nama_prodi'] ?? '-' }}</td>
                                                <td><span class="badge bg-info">{{ $enrollment['golongan_ukt']['level'] ?? '-' }}</span></td>
                                                <td><strong>Rp {{ number_format($enrollment['golongan_ukt']['nominal'] ?? 0, 0, ',', '.') }}</strong></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('previewBtn').addEventListener('click', function() {
    // Show loading
    document.getElementById('previewSection').style.display = 'block';
    document.getElementById('previewContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat preview...</p></div>';

    // Fetch preview data (tidak perlu kirim periode karena otomatis)
    fetch('{{ route("staff.detail-buatTagihanUkt.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + data.error + '</div>';
            return;
        }

        let html = `
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4><i class="fas fa-users"></i></h4>
                            <h5>${data.total_mahasiswa}</h5>
                            <small>Total Mahasiswa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4><i class="fas fa-plus-circle"></i></h4>
                            <h5>${data.akan_dibuatkan}</h5>
                            <small>Akan Dibuat</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4><i class="fas fa-check-circle"></i></h4>
                            <h5>${data.sudah_ada_tagihan}</h5>
                            <small>Sudah Ada</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4><i class="fas fa-calendar"></i></h4>
                            <h6>${data.tahun_akademik}</h6>
                            <small>Tahun Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-info">
                <strong>Periode:</strong> ${data.periode}
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Nominal UKT</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        data.mahasiswa.forEach(mhs => {
            const statusClass = mhs.status === 'Sudah Ada' ? 'badge bg-warning text-dark' : 'badge bg-success';
            const statusIcon = mhs.status === 'Sudah Ada' ? 'fas fa-check' : 'fas fa-plus';
            html += `
                <tr>
                    <td><span class="badge bg-secondary">${mhs.nim}</span></td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                    <td><strong>Rp ${mhs.nominal}</strong></td>
                    <td><span class="${statusClass}"><i class="${statusIcon}"></i> ${mhs.status}</span></td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('previewContent').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan saat memuat preview</div>';
    });
});

// Form submission with confirmation
document.getElementById('uktForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const jatuhTempo = document.getElementById('tanggal_jatuh_tempo').value;
    if (!jatuhTempo) {
        alert('Harap pilih tanggal jatuh tempo terlebih dahulu.');
        return;
    }

    // Validasi tanggal jatuh tempo minimal besok
    const today = new Date();
    const selectedDate = new Date(jatuhTempo);
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    if (selectedDate < tomorrow) {
        alert('Tanggal jatuh tempo harus minimal besok.');
        return;
    }

    if (confirm('Apakah Anda yakin ingin membuat tagihan UKT untuk semua mahasiswa aktif? Proses ini tidak dapat dibatalkan.\n\nTanggal Jatuh Tempo: ' + new Date(jatuhTempo).toLocaleDateString('id-ID'))) {
        // Disable button untuk mencegah double submit
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

        this.submit();
    }
});

// Set minimum date untuk jatuh tempo (besok)
document.addEventListener('DOMContentLoaded', function() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowString = tomorrow.toISOString().split('T')[0];
    document.getElementById('tanggal_jatuh_tempo').setAttribute('min', tomorrowString);

    // Set default jatuh tempo 7 hari dari sekarang
    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 7);
    const nextWeekString = nextWeek.toISOString().split('T')[0];
    document.getElementById('tanggal_jatuh_tempo').value = nextWeekString;
});
</script>
@endpush
@endsection