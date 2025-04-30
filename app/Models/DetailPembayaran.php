<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'detail_pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_aktivitas_pembayaran',
        'nominal',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'kode_referensi',
        'bukti_pembayaran_path',
        'status',
        'verified_by',
        'verified_at',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the pembayaran UKT that owns the detail pembayaran.
     */
    public function pembayaranUkt()
    {
        return $this->belongsTo(PembayaranUktSemester::class, 'id_aktivitas_pembayaran');
    }

    /**
     * Get the staff that verified the payment.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(Staff::class, 'verified_by');
    }
}