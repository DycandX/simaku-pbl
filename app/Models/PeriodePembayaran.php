<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePembayaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'periode_pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_tahun_akademik',
        'nama_periode',
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
     * Get the tahun akademik that owns the periode pembayaran.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik');
    }

    /**
     * Get the UKT semesters for the periode pembayaran.
     */
    public function uktSemesters()
    {
        return $this->hasMany(UktSemester::class, 'id_periode_pembayaran');
    }

    /**
     * Scope a query to only include active payment periods.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}