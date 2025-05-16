@extends('layouts.staff-app')

@section('title', 'Beasiswa')

@section('header', 'Beasiswa Mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Beasiswa Mahasiswa</h2>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-48">
                <select id="prodiFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">D4 - Manajemen Bisnis</option>
                    <option value="d3-akuntansi">D3 - Akuntansi</option>
                    <option value="d4-mb">D4 - Manajemen Bisnis</option>
                    <option value="d4-mbi">D4 - Manajemen Bisnis Internasional</option>
                </select>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" 
                    style="background-color: #3B82F6; border: none;">
                Filter
            </button>
            <div class="flex-1 min-w-64">
                <input type="text" placeholder="Search..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Title Section -->
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-700">Semua Beasiswa</h3>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Beasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">01</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Zirillia Syadha</td>
                        <td class="px-4 py-3 text-sm text-gray-900">4.33.23.5.19</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Administrasi Bisnis</td>
                        <td class="px-4 py-3 text-sm text-gray-900">D4 - Manajemen Bisnis Internasional</td>
                        <td class="px-4 py-3 text-sm text-gray-900">KIP-Kuliah</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                                    style="background-color: #3B82F6; border: none;">
                                Lihat Beasiswa
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">02</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Fadhil Ramadhan</td>
                        <td class="px-4 py-3 text-sm text-gray-900">4.33.23.5.20</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Administrasi Bisnis</td>
                        <td class="px-4 py-3 text-sm text-gray-900">D4 - Manajemen Bisnis Internasional</td>
                        <td class="px-4 py-3 text-sm text-gray-900">KIP-Kuliah</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                                    style="background-color: #3B82F6; border: none;">
                                Lihat Beasiswa
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">03</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Aisyah Hanifah</td>
                        <td class="px-4 py-3 text-sm text-gray-900">4.33.23.5.21</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Administrasi Bisnis</td>
                        <td class="px-4 py-3 text-sm text-gray-900">D4 - Manajemen Bisnis Internasional</td>
                        <td class="px-4 py-3 text-sm text-gray-900">KIP-Kuliah</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                                    style="background-color: #3B82F6; border: none;">
                                Lihat Beasiswa
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">04</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Yudha Prasetyo</td>
                        <td class="px-4 py-3 text-sm text-gray-900">4.33.23.5.22</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Administrasi Bisnis</td>
                        <td class="px-4 py-3 text-sm text-gray-900">D4 - Manajemen Bisnis Internasional</td>
                        <td class="px-4 py-3 text-sm text-gray-900">KIP-Kuliah</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                                    style="background-color: #3B82F6; border: none;">
                                Lihat Beasiswa
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">05</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Lestari Widya</td>
                        <td class="px-4 py-3 text-sm text-gray-900">4.33.23.5.23</td>
                        <td class="px-4 py-3 text-sm text-gray-900">Administrasi Bisnis</td>
                        <td class="px-4 py-3 text-sm text-gray-900">D4 - Manajemen Bisnis Internasional</td>
                        <td class="px-4 py-3 text-sm text-gray-900">KIP-Kuliah</td>
                        <td class="px-4 py-3">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors"
                                    style="background-color: #3B82F6; border: none;">
                                Lihat Beasiswa
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-700">
            Showing 1-5 of 50
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">
                Previous
            </button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                    style="background-color: #3B82F6; border: none;">
                1
            </button>
            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">
                2
            </button>
            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">
                3
            </button>
            <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">
                Next
            </button>
        </div>
    </div>
</div>

<script>
// Add filter functionality
document.getElementById('prodiFilter').addEventListener('change', function() {
    // Add your filter logic here
    console.log('Filter changed:', this.value);
});

// Add search functionality
const searchInput = document.querySelector('input[placeholder="Search..."]');
searchInput.addEventListener('input', function() {
    // Add your search logic here
    console.log('Search:', this.value);
});
</script>
@endsection