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
                        <strong>Info:</strong> Sistem akan membuat tagihan UKT untuk semua mahasiswa yang terdaftar pada tahun akademik aktif.
                    </div>

                    <form action="{{ route('staff.detail-buatTagihanUkt.create') }}" method="POST" id="uktForm">
                        @csrf
                        <div class="mb-3">
                            <label for="tagihan" class="form-label">Periode Pembayaran</label>
                            <select class="form-select" id="tagihan" name="tagihan" required>
                                <option value="">-- Pilih Periode Pembayaran --</option>
                                @foreach ($periodePembayaran as $periode)
                                    <option value="{{ $periode['nama_periode'] }}">{{ $periode['nama_periode'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_tagihan" class="form-label">Tanggal Tagihan</label>
                            <input type="date" class="form-control" id="tanggal_tagihan" name="tanggal_tagihan" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>Buat Tagihan UKT untuk semua mahasiswa aktif</textarea>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-info" id="previewBtn">Preview Mahasiswa</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Buat Tagihan (Batch)</button>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div id="previewSection" class="mt-4" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6>Preview Mahasiswa yang Akan Dibuatkan Tagihan</h6>
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
                                <h6>Mahasiswa Aktif ({{ count($mahasiswaData) }} orang)</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
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
                                                <td>{{ $enrollment['mahasiswa']['nim'] ?? '-' }}</td>
                                                <td>{{ $enrollment['mahasiswa']['nama_lengkap'] ?? '-' }}</td>
                                                <td>{{ $enrollment['program_studi']['nama_prodi'] ?? '-' }}</td>
                                                <td>{{ $enrollment['golongan_ukt']['level'] ?? '-' }}</td>
                                                <td>Rp {{ number_format($enrollment['golongan_ukt']['nominal'] ?? 0, 0, ',', '.') }}</td>
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
    const periode = document.getElementById('tagihan').value;

    if (!periode) {
        alert('Pilih periode pembayaran terlebih dahulu');
        return;
    }

    // Show loading
    document.getElementById('previewSection').style.display = 'block';
    document.getElementById('previewContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    // Fetch preview data
    fetch('{{ route("staff.detail-buatTagihanUkt.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            periode: periode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
            return;
        }

        let html = `
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5>${data.total_mahasiswa}</h5>
                            <small>Total Mahasiswa</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5>${data.akan_dibuatkan}</h5>
                            <small>Akan Dibuat</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5>${data.sudah_ada_tagihan}</h5>
                            <small>Sudah Ada</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h6>${data.tahun_akademik}</h6>
                            <small>Tahun Akademik</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
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
            const statusClass = mhs.status === 'Sudah Ada' ? 'badge bg-warning' : 'badge bg-success';
            html += `
                <tr>
                    <td>${mhs.nim}</td>
                    <td>${mhs.nama}</td>
                    <td>${mhs.prodi}</td>
                    <td>Rp ${mhs.nominal}</td>
                    <td><span class="${statusClass}">${mhs.status}</span></td>
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
        document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat preview</div>';
    });
});

// Form submission with confirmation
document.getElementById('uktForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (confirm('Apakah Anda yakin ingin membuat tagihan UKT untuk semua mahasiswa aktif? Proses ini tidak dapat dibatalkan.')) {
        this.submit();
    }
});
</script>
@endpush
@endsection