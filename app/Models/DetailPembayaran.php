<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    use HasFactory;

    protected $table = 'detail_pembayaran';

    protected $fillable = [
        'id_pembayaran_ukt_semester',
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

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function pembayaranUktSemester()
    {
        return $this->belongsTo(PembayaranUktSemester::class, 'id_pembayaran_ukt_semester');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Staff::class, 'verified_by');
    }
}
