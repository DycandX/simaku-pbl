<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UktSemester extends Model
{
    use HasFactory;

    protected $table = 'ukt_semester';

    protected $fillable = [
        'id_enrollment',
        'status',
        'id_periode_pembayaran',
        'jumlah_ukt'
    ];

    protected $casts = [
        'jumlah_ukt' => 'decimal:2'
    ];

    // Relationship dengan EnrollmentMahasiswa
    public function enrollment()
    {
        return $this->belongsTo(EnrollmentMahasiswa::class, 'id_enrollment');
    }

    // Relationship dengan PeriodePembayaran
    public function periodePembayaran()
    {
        return $this->belongsTo(PeriodePembayaran::class, 'id_periode_pembayaran');
    }

    // Relationship dengan PembayaranUktSemester
    public function pembayaran()
    {
        return $this->hasMany(PembayaranUktSemester::class, 'id_ukt_semester');
    }

    // Relationship dengan PengajuanCicilan
    public function pengajuanCicilan()
    {
        return $this->hasMany(PengajuanCicilan::class, 'id_ukt_semester');
    }

    // Get total paid amount
    public function getTotalPaidAttribute()
    {
        return $this->pembayaran()
                    ->where('status', 'terbayar')
                    ->sum('nominal_tagihan');
    }

    // Get remaining amount
    public function getRemainingAmountAttribute()
    {
        return $this->jumlah_ukt - $this->total_paid;
    }

    // Check if fully paid
    public function isFullyPaid()
    {
        return $this->remaining_amount <= 0;
    }
}
