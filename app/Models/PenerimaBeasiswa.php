<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaBeasiswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'penerima_beasiswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'nominal' => 'decimal:2',
    ];

    /**
     * Get the mahasiswa that owns the penerima beasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    /**
     * Get the beasiswa that owns the penerima beasiswa.
     */
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class, 'id_beasiswa');
    }

    /**
     * Get the staff that created the penerima beasiswa.
     */
    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    /**
     * Scope a query to only include active scholarship recipients.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}