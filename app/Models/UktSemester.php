<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UktSemester extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ukt_semester';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim',
        'id_golongan_ukt',
        'status',
        'id_periode_pembayaran',
        'jumlah_ukt',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jumlah_ukt' => 'decimal:2',
    ];

    /**
     * Get the mahasiswa that owns the UKT semester.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    /**
     * Get the golongan UKT that owns the UKT semester.
     */
    public function golonganUkt()
    {
        return $this->belongsTo(GolonganUkt::class, 'id_golongan_ukt');
    }

    /**
     * Get the periode pembayaran that owns the UKT semester.
     */
    public function periodePembayaran()
    {
        return $this->belongsTo(PeriodePembayaran::class, 'id_periode_pembayaran');
    }

    /**
     * Get the pembayaran UKT for the UKT semester.
     */
    public function pembayaranUkt()
    {
        return $this->hasMany(PembayaranUktSemester::class, 'id_ukt_semester');
    }
}