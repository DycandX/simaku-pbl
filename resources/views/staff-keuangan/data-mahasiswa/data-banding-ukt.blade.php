@extends('layouts.staff-app')

@section('title', 'Banding UKT - SIMAKU')

@section('header', 'Banding UKT')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="card-title mb-4">
                <h5>Data Banding UKT</h5>
            </div>

            <form>
                <div class="form-group mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" value="ZIRILDA SYAFIRA" disabled>
                </div>

                <div class="form-group mb-3">
                    <label for="ukt_saat_ini" class="form-label">UKT Saat Ini</label>
                    <input type="text" class="form-control" id="ukt_saat_ini" value="Golongan - IV" disabled>
                </div>

                <div class="form-group mb-3">
                    <label for="hasil_banding" class="form-label">Hasil Banding UKT</label>
                    <select class="form-control" id="hasil_banding">
                        <option selected>Golongan - III</option>
                        <option>Golongan - II</option>
                        <option>Golongan - I</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="keterangan" class="form-label">Keterangan Banding</label>
                    <textarea class="form-control" id="keterangan" rows="3">Banding UKT Berhasil Dilakukan</textarea>
                </div>

                <button type="button" class="btn btn-primary">Simpan</button>
                <a href="#" class="btn btn-outline-primary float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
