<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranUktSemester extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pembayaran_ukt_semester';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim',
        'id_ukt_semester',
        'nomor_cicilan',
        'nominal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nominal_tagihan' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
    ];

    /**
     * Get the mahasiswa that owns the pembayaran UKT.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    /**
     * Get the UKT semester that owns the pembayaran UKT.
     */
    public function uktSemester()
    {
        return $this->belongsTo(UktSemester::class, 'id_ukt_semester');
    }

    /**
     * Get the detail pembayaran for the pembayaran UKT.
     */
    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class, 'id_aktivitas_pembayaran');
    }
}