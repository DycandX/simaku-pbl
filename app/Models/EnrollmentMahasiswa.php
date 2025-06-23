<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'enrollment_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'id_program_studi', // Ganti program_studi dengan id_program_studi
        'id_golongan_ukt',
        'id_kelas',
        'id_tingkat',
        'id_tahun_akademik'
    ];

    // Relationship dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    // Relationship dengan ProgramStudi
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_program_studi');
    }

    // Relationship dengan GolonganUkt
    public function golonganUkt()
    {
        return $this->belongsTo(GolonganUkt::class, 'id_golongan_ukt');
    }

    // Relationship dengan Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Relationship dengan Tingkat
    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class, 'id_tingkat');
    }

    // Relationship dengan TahunAkademik
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik');
    }

    // Relationship dengan UktSemester
    public function uktSemester()
    {
        return $this->hasMany(UktSemester::class, 'id_enrollment');
    }

    // Relationship dengan PembayaranUktSemester
    public function pembayaranUkt()
    {
        return $this->hasMany(PembayaranUktSemester::class, 'id_enrollment');
    }

    // Relationship dengan PengajuanCicilan
    public function pengajuanCicilan()
    {
        return $this->hasMany(PengajuanCicilan::class, 'id_enrollment');
    }

    // Get active UKT semester
    public function getActiveUktSemester()
    {
        return $this->uktSemester()->where('status', 'aktif')->first();
    }

    // Get unpaid pembayaran
    public function getUnpaidPembayaran()
    {
        return $this->pembayaranUkt()
                    ->where('status', 'belum_bayar')
                    ->orderBy('tanggal_jatuh_tempo', 'asc')
                    ->get();
    }
}
