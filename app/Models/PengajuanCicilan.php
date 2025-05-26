<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCicilan extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_cicilan';
    protected $fillable = [
        'nama_lengkap',
        'nim',
        'fakultas',
        'program_studi',
        'tagihan',
        'angsuran',
        'file_path',
        'status'
    ];
}

