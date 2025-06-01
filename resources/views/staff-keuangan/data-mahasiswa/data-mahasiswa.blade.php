@extends('layouts.staff-app')

@section('title', 'Data Mahasiswa - SIMAKU')

@section('header', 'Data Mahasiswa')

@section('content')
<div class="row">
    <!-- Filter Section -->
    <div class="col-12 mb-4">
        <div class="filter-section">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterAngkatan">Angkatan</label>
                        <select class="form-control" id="filterAngkatan">
                            <option value="">Semua Angkatan</option>
                            @foreach ($years as $year)
                                <option value="{{ $year['id'] }}" {{ $angkatan == $year['id'] ? 'selected' : '' }}>
                                    {{ $year['tahun_akademik'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="filterProdi">Program Studi</label>
                        <select class="form-control" id="filterProdi">
                            <option value="">Semua Program Studi</option>
                            @foreach ($programs as $prodi)
                                <option value="{{ $prodi['id'] }}" {{ $prodi['id'] == $prodi ? 'selected' : '' }}>
                                    {{ $prodi['program_studi']['nama_prodi'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="searchInput">Cari</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Nama/NIM..." value="{{ $searchTerm }}">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <button type="button" class="btn btn-primary" id="btnFilter">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Mahasiswa Table -->
    <div class="col-12">
        <div class="table-container card">
            <div class="card-header">
                <h3 class="card-title">Daftar Mahasiswa</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Angkatan</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student['mahasiswa']['nama_lengkap'] }}</td>
                                    <td>{{ $student['mahasiswa']['nim'] }}</td>
                                    <td>{{ $student['tahun_akademik']['tahun_akademik'] }}</td>
                                    <td>{{ $student['kelas']['faculty_name'] }}</td>
                                    <td>{{ $student['kelas']['program_name'] }}</td>
                                    <td>
                                        <a href="{{ route('staff-keuangan.data-mahasiswa.detail-data-mahasiswa', ['nim' => $student['mahasiswa']['nim']]) }}" class="btn btn-view">
                                            <i class="fas fa-eye"></i> Lihat Mahasiswa
                                        </a>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#btnFilter').on('click', function() {
            const angkatan = $('#filterAngkatan').val();
            const prodi = $('#filterProdi').val();
            const search = $('#searchInput').val();
            const url = `{{ url('staff-keuangan/data-mahasiswa') }}?angkatan=${angkatan}&prodi=${prodi}&search=${search}`;
            window.location.href = url;
        });
    });
</script>
@endsection
