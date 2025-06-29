@extends('layouts.staff-app')

@section('title', 'Beasiswa - SIMAKU')

@section('header', 'Beasiswa Mahasiswa')

@section('content')
<!-- Beasiswa Mahasiswa Table -->
<div class="col-12">
    <div class="table-container card">
        <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa Penerima Beasiswa</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="beasiswaTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Nama Beasiswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paginatedData as $index => $beasiswa)
                        <tr>
                            <td>{{ $paginatedData->firstItem() + $index }}</td>
                            <td>{{ $beasiswa['mahasiswa']['nama_lengkap'] }}</td>
                            <td>{{ $beasiswa['mahasiswa']['nim'] }}</td>
                            <td>{{ $beasiswa['program_studi']['nama_prodi'] }}</td>
                            <td>{{ $beasiswa['beasiswa']['nama_beasiswa'] }}</td>
                            <td>
                                <a href="{{ route('staff-keuangan.beasiswa.staff-detail-beasiswa', ['nim' => $beasiswa['mahasiswa']['nim']]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data penerima beasiswa ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="pagination-info">
                        Menampilkan {{ $paginatedData->firstItem() }} - {{ $paginatedData->lastItem() }} dari {{ $paginatedData->total() }} data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="d-flex justify-content-end">
                        {{ $paginatedData->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // Print functionality
        $('#btnCetak').on('click', function() {
            window.print();
        });

        // Export Excel functionality
        $('#btnExport').on('click', function() {
            alert('Ekspor Excel berhasil!');
        });
    });
</script>
@endsection
