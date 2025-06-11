<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PembayaranUktSemester extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_ukt_semester';

    protected $fillable = [
        'id_enrollment',
        'id_ukt_semester',
        'id_jenis_pembayaran',
        'total_cicilan',
        'nominal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
        'id_pengajuan_cicilan'
    ];

    protected $casts = [
        'nominal_tagihan' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date'
    ];

    // Relationship dengan EnrollmentMahasiswa
    public function enrollment()
    {
        return $this->belongsTo(EnrollmentMahasiswa::class, 'id_enrollment');
    }

    // Relationship dengan UktSemester
    public function uktSemester()
    {
        return $this->belongsTo(UktSemester::class, 'id_ukt_semester');
    }

    // Relationship dengan JenisPembayaran
    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran');
    }

    // Relationship dengan PengajuanCicilan
    public function pengajuanCicilan()
    {
        return $this->belongsTo(PengajuanCicilan::class, 'id_pengajuan_cicilan');
    }

    public function detailPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class, 'id_pembayaran_ukt_semester');
    }


    // Check if overdue
    public function isOverdue()
    {
        return $this->tanggal_jatuh_tempo < Carbon::now()->toDateString()
               && $this->status === 'belum_bayar';
    }

    // Check if paid
    public function isPaid()
    {
        return $this->status === 'terbayar';
    }

    // Get mahasiswa through enrollment
    public function getMahasiswaAttribute()
    {
        return $this->enrollment->mahasiswa;
    }

    // Get cicilan ke berapa
    public function getCicilanKeAttribute()
    {
        return $this->jenisPembayaran->nama ?? 'Cicilan ' . $this->id;
    }

    // Auto update status jika overdue
    public function updateOverdueStatus()
    {
        if ($this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        }
    }

    // Scope untuk belum bayar
    public function scopeBelumBayar($query)
    {
        return $query->where('status', 'belum_bayar');
    }

    // Scope untuk terbayar
    public function scopeTerbayar($query)
    {
        return $query->where('status', 'terbayar');
    }

    // Scope untuk overdue
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}