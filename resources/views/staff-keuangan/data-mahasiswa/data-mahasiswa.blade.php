@extends('layouts.staff-app')

@section('title', 'Data Mahasiswa')

@section('header', 'Staff - Data Mahasiswa')

@section('content_header')
    <h1>Data Mahasiswa</h1>
@stop

@section('content')
    <div class="container">
        <!-- Filter Form -->
        <div class="d-flex mb-3">
            <!-- Dropdown untuk Angkatan -->
            <select class="form-control w-25 mr-2" id="angkatan">
                <option value="2023">Angkatan 2024</option>
                <option value="2023">Angkatan 2023</option>
                <option value="2022">Angkatan 2022</option>
                <option value="2021">Angkatan 2021</option>
            </select>

            <!-- Dropdown untuk Prodi -->
            <select class="form-control w-25 mr-2" id="prodi">
                <option value="all">Semua Prodi</option>
                <option value="Ekonomi Pembangunan">Ekonomi Pembangunan</option>
                <option value="Ilmu Gizi">Ilmu Gizi</option>
                <option value="Informatika">Informatika</option>
                <option value="Sastra Indonesia">Sastra Indonesia</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Akuntansi">Akuntansi</option>
                <!-- Tambahkan prodi lainnya sesuai kebutuhan -->
            </select>

            <!-- Tombol Filter -->
            <button class="btn btn-primary ml-2" id="filterButton">Filter</button>

            <!-- Search Input on the Right -->
            <div class="ml-auto">
                <input type="text" class="form-control" id="searchInput" placeholder="Search..." style="width: 250px;">
            </div>
        </div>

        <!-- Table for Data Mahasiswa -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Angkatan</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                </tr>
            </thead>
            <tbody id="mahasiswaTable">
                @foreach($mahasiswaData as $index => $mahasiswa)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mahasiswa['nama_lengkap'] }}</td>
                        <td>{{ $mahasiswa['nim'] }}</td>
                        <td>{{ $mahasiswa['angkatan'] }}</td>
                        <td>{{ $mahasiswa['jurusan'] }}</td>
                        <td>{{ $mahasiswa['prodi'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        // Fungsi untuk filter data berdasarkan dropdown dan search input
        document.getElementById('filterButton').addEventListener('click', function () {
            let angkatan = document.getElementById('angkatan').value;
            let prodi = document.getElementById('prodi').value;
            let searchInput = document.getElementById('searchInput').value.toLowerCase();
            
            let tableRows = document.querySelectorAll('#mahasiswaTable tr');

            tableRows.forEach(function(row) {
                let namaMahasiswa = row.cells[1].textContent.toLowerCase();
                let nim = row.cells[2].textContent;
                let angkatanCell = row.cells[3].textContent;
                let jurusanCell = row.cells[4].textContent;
                let prodiCell = row.cells[5].textContent;

                // Filter berdasarkan angkatan, prodi, dan pencarian nama
                if (
                    (angkatan === 'all' || angkatanCell === angkatan) &&
                    (prodi === 'all' || prodiCell === prodi) &&
                    (namaMahasiswa.includes(searchInput) || nim.includes(searchInput))
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@stop
