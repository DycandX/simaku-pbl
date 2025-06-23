<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_kelas',
        'id_prodi',
        'tahun_angkatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tahun_angkatan' => 'integer',
    ];

    /**
     * Get the program studi that owns the kelas.
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }

    /**
     * Get the enrollments for the kelas.
     */
    public function enrollments()
    {
        return $this->hasMany(EnrollmentMahasiswa::class, 'id_kelas');
    }

    /**
     * Get the mahasiswa in this kelas.
     */
    public function mahasiswa()
    {
        return $this->hasManyThrough(
            Mahasiswa::class,
            EnrollmentMahasiswa::class,
            'id_kelas', // Foreign key on enrollment_mahasiswa table
            'nim', // Foreign key on mahasiswa table
            'id', // Local key on kelas table
            'nim' // Local key on enrollment_mahasiswa table
        );
    }
}