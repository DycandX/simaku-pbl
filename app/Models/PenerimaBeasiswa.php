<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use App\Models\Staff;

class PenerimaBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'penerima_beasiswa';

    protected $fillable = [
        'nim',
        'id_beasiswa',
        'tanggal_mulai',
        'tanggal_selesai',
        'nominal',
        'status',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'nominal' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Relasi ke tabel beasiswa.
     */
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class, 'id_beasiswa');
    }

    /**
     * Relasi ke staff yang membuat entri ini.
     */
    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Scope untuk penerima yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
