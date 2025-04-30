<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentMahasiswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'enrollment_mahasiswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim',
        'id_kelas',
        'id_tingkat',
        'id_tahun_akademik',
    ];

    /**
     * Get the mahasiswa that owns the enrollment.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    /**
     * Get the kelas that owns the enrollment.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    /**
     * Get the tingkat that owns the enrollment.
     */
    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class, 'id_tingkat');
    }

    /**
     * Get the tahun akademik that owns the enrollment.
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik');
    }
}