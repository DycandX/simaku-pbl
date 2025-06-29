@extends('layouts.admin-app')

@section('title', 'Kelola Mahasiswa')

@section('styles')
<style>
    /* Filter Row Styling */
    .filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }

    .filter-left {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-btn {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 20px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .filter-btn:hover {
        background-color: #2e59d9;
    }

    /* Search Box */
    .search-box {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px 15px;
        width: 250px;
        font-size: 14px;
        outline: none;
    }

    .search-box:focus {
        border-color: #4e73df;
    }

    /* Table Custom Styling */
    .table thead th {
        background-color: #f8f9fc;
        font-weight: 600;
        color: #666;
        font-size: 14px;
        padding: 12px 15px;
        border-bottom: 2px solid #e3e6f0;
    }

    .table tbody td {
        padding: 12px 15px;
        font-size: 14px;
        color: #5a5c69;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    /* Pagination Styling */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 10px 0;
    }

    .pagination-info {
        color: #6c757d;
        font-size: 14px;
    }

    .page-controls {
        display: flex;
        gap: 5px;
    }

    .page-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #dee2e6;
        background-color: white;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    /* Pagination Link Styling */
    .page-btn {
        text-decoration: none;
        color: #6c757d;
    }

    .page-btn.active {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    a.page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    a.page-btn:hover {
        text-decoration: none;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .page-btn[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Mahasiswa info styling */
    .mahasiswa-info {
        display: flex;
        flex-direction: column;
    }

    .mahasiswa-name {
        font-weight: 600;
        color: #2d3748;
    }

    .mahasiswa-nim {
        font-size: 12px;
        color: #718096;
        margin-top: 2px;
    }

    /* Photo styling */
    .mahasiswa-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e3e6f0;
    }

    .photo-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 14px;
        border: 2px solid #e3e6f0;
    }

    /* Contact info styling */
    .contact-info {
        font-size: 13px;
        color: #4a5568;
    }

    .alamat-text {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        background-color: #f6c23e;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-edit:hover {
        background-color: #dda20a;
        text-decoration: none;
        color: white;
    }

    .btn-delete {
        background-color: #e74a3b;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-delete:hover {
        background-color: #c0392b;
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-left {
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .search-box {
            width: 100%;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
        }

        .alamat-text {
            max-width: 150px;
        }
    }
</style>
@endsection

@section('header', 'Dashboard Kelola Mahasiswa')

@section('header_button')
<a href="{{ route('admin.mahasiswa.create') }}" class="btn-add">
    <i class="fas fa-plus"></i>
    Tambah Mahasiswa
</a>
@endsection

@section('content')
<form method="GET" action="{{ route('admin.mahasiswa') }}" id="filterForm">
    <div class="filter-row">
        <div class="filter-left">
            @if(request('search'))
                <a href="{{ route('admin.mahasiswa') }}" class="btn btn-secondary" style="padding: 8px 15px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 5px; font-size: 14px;">
                    Reset
                </a>
            @endif
        </div>

        <div class="search-wrapper">
            <input type="text" name="search" placeholder="Cari nama, NIM, alamat, atau telepon..." class="search-box" id="searchInput" value="{{ request('search') }}">
        </div>
    </div>
</form>

<div class="table-container">
    <div class="card-header">
        <h3 class="card-title">Semua Mahasiswa</h3>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th style="width: 120px;">No. Telepon</th>
                    <th style="width: 120px;">Tanggal Dibuat</th>
                    <th style="width: 120px;">Terakhir Diubah</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $index => $item)
                    <tr>
                        <td>{{ ($mahasiswa->firstItem() ?? 0) + $index }}</td>
                        <td class="text-center">
                            @if($item['foto_path'])
                                <img src="{{ asset('storage/' . $item['foto_path']) }}" alt="Foto {{ $item['nama_lengkap'] }}" class="mahasiswa-photo">
                            @else
                                <div class="photo-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="mahasiswa-nim">{{ $item['nim'] }}</span>
                        </td>
                        <td>
                            <div class="mahasiswa-info">
                                <span class="mahasiswa-name">{{ $item['nama_lengkap'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="alamat-text" title="{{ $item['alamat'] }}">
                                {{ $item['alamat'] }}
                            </div>
                        </td>
                        <td>
                            <span class="contact-info">{{ $item['no_telepon'] }}</span>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.mahasiswa.edit', $item['nim']) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn-delete" onclick="confirmDelete('{{ $item['nim'] }}', '{{ $item['nama_lengkap'] }}')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            @if(request('search'))
                                Tidak ada mahasiswa yang sesuai dengan pencarian "{{ request('search') }}".
                            @else
                                Tidak ada data mahasiswa.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-wrapper">
            <div class="pagination-info">
                @if($mahasiswa->total() > 0)
                    Showing {{ $mahasiswa->firstItem() ?? 0 }}-{{ $mahasiswa->lastItem() ?? 0 }} of {{ $mahasiswa->total() }}
                    @if(request('search'))
                        (filtered by Search: "{{ request('search') }}")
                    @endif
                @else
                    No mahasiswa found
                @endif
            </div>
            <div class="page-controls">
                @if ($mahasiswa->onFirstPage())
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <a href="{{ $mahasiswa->previousPageUrl() }}" class="page-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($mahasiswa->getUrlRange(1, $mahasiswa->lastPage()) as $page => $url)
                    @if ($page == $mahasiswa->currentPage())
                        <button class="page-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($mahasiswa->hasMorePages())
                    <a href="{{ $mahasiswa->nextPageUrl() }}" class="page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus mahasiswa <strong id="mahasiswaName"></strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Search functionality with debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500); // 500ms delay for better UX
    });

    // Handle Enter key for search
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            $('#filterForm').submit();
        }
    });

    // Edit button functionality
    $('.btn-edit').on('click', function(e) {
        var row = $(this).closest('tr');
        var mahasiswaName = row.find('.mahasiswa-name').text();
        console.log('Editing mahasiswa:', mahasiswaName);
    });

    // Add mahasiswa button functionality
    $('.btn-add').on('click', function() {
        console.log('Adding new mahasiswa');
    });

    // Clear search when clicking reset
    $('a[href*="admin.mahasiswa"]:contains("Reset")').on('click', function() {
        $('#searchInput').val('');
    });
});

// Delete confirmation function
function confirmDelete(id, name) {
    $('#mahasiswaName').text(name);
    $('#deleteForm').attr('action', '/admin/mahasiswa/' + nim);
    $('#deleteModal').modal('show');
}

// Handle delete form submission
$('#deleteForm').on('submit', function(e) {
    e.preventDefault();

    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    var originalText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');

    // Submit form via AJAX
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            $('#deleteModal').modal('hide');

            // Show success message
            if (response.success) {
                // Refresh page after short delay
                setTimeout(function() {
                    window.location.reload();
                }, 500);

                // You can also show a toast notification here
                console.log('Mahasiswa berhasil dihapus');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error deleting mahasiswa:', error);

            // Reset button state
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);

            // Show error message
            alert('Terjadi kesalahan saat menghapus mahasiswa. Silakan coba lagi.');
        }
    });
});

// Reset modal when closed
$('#deleteModal').on('hidden.bs.modal', function() {
    var submitBtn = $('#deleteForm button[type="submit"]');
    submitBtn.prop('disabled', false);
    submitBtn.html('Hapus');
});
</script>
@endsection