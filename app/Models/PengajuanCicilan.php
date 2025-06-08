<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCicilan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cicilan';

    protected $fillable = [
        'id_enrollment',
        'id_ukt_semester',
        'jumlah_angsuran_diajukan',
        'jumlah_angsuran_disetujui',
        'alasan_pengajuan',
        'file_path',
        'status',
        'approved_by',
        'approved_at',
        'catatan_approval'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
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

    // Relationship dengan Staff (approver)
    public function approver()
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }

    // Relationship dengan PembayaranUktSemester
    public function pembayaran()
    {
        return $this->hasMany(PembayaranUktSemester::class, 'id_pengajuan_cicilan');
    }

    // Check if approved
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Check if rejected
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Check if pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Get mahasiswa through enrollment
    public function getMahasiswaAttribute()
    {
        return $this->enrollment->mahasiswa;
    }

    // Calculate cicilan amount
    public function getCicilanAmountAttribute()
    {
        if ($this->isApproved() && $this->jumlah_angsuran_disetujui > 0) {
            return $this->uktSemester->jumlah_ukt / $this->jumlah_angsuran_disetujui;
        }
        return 0;
    }
}