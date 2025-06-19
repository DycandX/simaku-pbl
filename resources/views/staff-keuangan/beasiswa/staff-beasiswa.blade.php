@extends('layouts.staff-app')

@section('title', 'Beasiswa - SIMAKU')

@section('header', 'Staff - Beasiswa Pendidikan')

@section('content')
<div class="container-fluid">
    <!-- Main Card -->
    
    <!-- Other Students with this Scholarship -->
    <div class="card card-outline card-primary mt-4">
        <div class="card-header">
            <h3 class="card-title">Mahasiswa Penerima Beasiswa</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Nama Beasiswa</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginatedData as $index => $beasiswa)
                        <tr>
                            <td>{{ $paginatedData->firstItem() + $index }}</td>
                            <td>{{ $beasiswa['mahasiswa']['nama_lengkap'] }}</td>
                            <td>{{ $beasiswa['mahasiswa']['nim'] }}</td>
                            <td>{{ $beasiswa['program_studi']['nama_prodi'] }}</td>
                            <td>{{ $beasiswa['beasiswa']['nama_beasiswa'] }}</td>
                            <td>
                                <a href="{{ route('staff-keuangan.beasiswa.staff-detail-beasiswa', ['nim' => $beasiswa['mahasiswa']['nim']]) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-left">
                <div class="pagination-info">
                    Menampilkan {{ $paginatedData->firstItem() }} - {{ $paginatedData->lastItem() }} dari {{ $paginatedData->total() }} penerima
                </div>
            </div>
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $paginatedData->links() }}
            </ul>
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
