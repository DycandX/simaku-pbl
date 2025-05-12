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
            <select class="form-control w-25 mr-2" id="angkatan">
                <option value="2023">Angkatan 2023</option>
                <option value="2022">Angkatan 2022</option>
                <option value="2021">Angkatan 2021</option>
                <!-- Add more options as needed -->
            </select>
            <select class="form-control w-25" id="jurusan">
                <option value="D4 - Manajemen Bisnis">D4 - Manajemen Bisnis</option>
                <option value="D3 - Akuntansi">D3 - Akuntansi</option>
                <!-- Add more options as needed -->
            </select>
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
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Angkatan</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="dataMahasiswa">
                <!-- Data will be dynamically inserted here -->
            </tbody>
        </table>
        
        <div>
            Showing <span id="startRow">1</span>-<span id="endRow">10</span> of 7500
        </div>

        <div class="pagination mt-3">
            <button id="prevBtn" class="btn btn-secondary">Previous</button>
            <button id="nextBtn" class="btn btn-secondary">Next</button>
        </div>
    </div>

    <script>
        const mahasiswaData = [
            { "nama_lengkap": "Zirlda Syafira", "nim": "4.33.23.5.19", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Fadhil Ramadhan", "nim": "4.33.23.5.20", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Aisyah Hanifah", "nim": "4.33.23.5.21", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Yudha Prasetyo", "nim": "4.33.23.5.22", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Lestari Widya", "nim": "4.33.23.5.24", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Zikri Alfarizi", "nim": "4.33.23.5.23", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Nabila Fauziah", "nim": "4.33.23.5.25", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Rizky Ramadhan", "nim": "4.33.23.5.26", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Salma Ayu Lestari", "nim": "4.33.23.5.27", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
            { "nama_lengkap": "Daffa Nursemaid", "nim": "4.33.23.5.28", "angkatan": 2023, "jurusan": "Administrasi Bisnis", "prodi": "D4 - Manajemen Bisnis Internasional" },
        ];

        let currentPage = 1;
        const rowsPerPage = 10;

        // Function to render mahasiswa data
        function renderTable(data) {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const slicedData = data.slice(start, end);

            const tableBody = document.getElementById('dataMahasiswa');
            tableBody.innerHTML = '';

            slicedData.forEach((mahasiswa, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${start + index + 1}</td>
                    <td>${mahasiswa.nama_lengkap}</td>
                    <td>${mahasiswa.nim}</td>
                    <td>${mahasiswa.angkatan}</td>
                    <td>${mahasiswa.jurusan}</td>
                    <td>${mahasiswa.prodi}</td>
                    <td><button class="btn btn-info">Lihat Mahasiswa</button></td>
                `;
                tableBody.appendChild(row);
            });

            // Update pagination info
            document.getElementById('startRow').textContent = start + 1;
            document.getElementById('endRow').textContent = Math.min(end, data.length);
        }

        // Pagination functionality
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable(mahasiswaData);
            }
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (currentPage * rowsPerPage < mahasiswaData.length) {
                currentPage++;
                renderTable(mahasiswaData);
            }
        });

        // Initial render
        renderTable(mahasiswaData);

        // Filter functionality
        document.getElementById('filterButton').addEventListener('click', () => {
            const angkatan = document.getElementById('angkatan').value;
            const jurusan = document.getElementById('jurusan').value;

            const filteredData = mahasiswaData.filter(mahasiswa => {
                return (angkatan === '' || mahasiswa.angkatan === parseInt(angkatan)) &&
                    (jurusan === '' || mahasiswa.jurusan === jurusan);
            });

            renderTable(filteredData);
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', () => {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();

            const filteredData = mahasiswaData.filter(mahasiswa => {
                return mahasiswa.nama_lengkap.toLowerCase().includes(searchQuery) || 
                       mahasiswa.nim.toLowerCase().includes(searchQuery);
            });

            renderTable(filteredData);
        });
    </script>
@stop
