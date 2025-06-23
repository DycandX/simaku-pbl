<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tahun_akademik';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_akademik',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the periode pembayaran for the tahun akademik.
     */
    public function periodePembayaran()
    {
        return $this->hasMany(PeriodePembayaran::class, 'id_tahun_akademik');
    }

    /**
     * Get the enrollments for the tahun akademik.
     */
    public function enrollments()
    {
        return $this->hasMany(EnrollmentMahasiswa::class, 'id_tahun_akademik');
    }

    /**
     * Scope a query to only include active academic years.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}