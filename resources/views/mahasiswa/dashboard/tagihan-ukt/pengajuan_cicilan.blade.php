@extends('layouts.app')

@section('title', 'Pengajuan Cicilan UKT - SIMAKU')

@section('header', 'Pengajuan Cicilan UKT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Upload Pengajuan Cicilan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengajuan.cicilan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Hidden Inputs --}}
                    @if(isset($uktSemester))
                        <input type="hidden" name="id_enrollment" value="{{ $uktSemester['id_enrollment'] }}">
                        {{-- <input type="hidden" name="id_ukt_semester" value="{{ $uktSemester['id'] }}"> --}}
                    @endif

                    {{-- Tagihan --}}
                    <div class="form-group row mb-3">
                        <label for="tagihan" class="col-sm-2 col-form-label">Nomor Tagihan</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" id="id_ukt_semester_display" name="id_ukt_semester_display"
                                value="{{ ($uktSemester['id']) ? 'INV' . str_pad($uktSemester['id'], 5, '0', STR_PAD_LEFT) : '-' }}">
                            <input type="hidden" name="id_ukt_semester" value="{{ $uktSemester['id'] }}">
                        </div>
                    </div>
                    {{-- <pre>{{ var_dump($uktSemester) }}</pre> --}}

                    {{-- Jumlah Angsuran Diajukan --}}
                    <div class="form-group row mb-3">
                        <label for="angsuran" class="col-sm-2 col-form-label">Angsuran</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="jumlah_angsuran_diajukan" name="jumlah_angsuran_diajukan" required>
                                <option value="" disabled selected>-- Pilih Jumlah Angsuran --</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>

                    {{-- Alasan Pengajuan --}}
                    <div class="form-group row mb-3">
                        <label for="alasan_pengajuan" class="col-sm-2 col-form-label">Alasan Pengajuan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alasan_pengajuan" id="alasan_pengajuan" rows="3" required></textarea>
                        </div>
                    </div>

                    {{-- Upload File PDF --}}
                    <div class="form-group row mb-4">
                        <label for="file_path" class="col-sm-2 col-form-label">Upload File</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="file_path" accept="application/pdf" required>
                            <small class="form-text text-muted">Upload surat permohonan cicilan dalam format PDF (Max: 2MB)</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin mengirim pengajuan cicilan?')">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                            <a href="{{ route('mahasiswa-dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('styles')
<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 1;
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary:hover {
        background-color: #375dd1;
        border-color: #375dd1;
    }

    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .custom-file-label::after {
        content: "Browse";
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Script untuk custom file input
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $('.custom-file-label').html(fileName);
        });

        // Browse button click handler
        $('#upload-btn').click(function() {
            $('#file').click();
        });
    });
</script>
@endsection