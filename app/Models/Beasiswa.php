<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use HasFactory;
    
    protected $table = 'beasiswa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_beasiswa',
        'jenis',
        'deskripsi',
        'periode_mulai',
        'periode_selesai',
        'nominal_max',
        'persyaratan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'nominal_max' => 'decimal:2',
    ];

    /**
     * Get the penerima beasiswa for the beasiswa.
     */
    public function penerima()
    {
        return $this->hasMany(PenerimaBeasiswa::class, 'id_beasiswa');
    }

    /**
     * Scope a query to only include active scholarships.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
