<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'nim';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim',
        'nama_lengkap',
        'alamat',
        'no_telepon',
        'email',
        'foto_path',
    ];

    /**
     * Get the user that owns the mahasiswa.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the enrollments for the mahasiswa.
     */
    public function enrollments()
    {
        return $this->hasMany(EnrollmentMahasiswa::class, 'nim');
    }

    /**
     * Get the UKT semester data for the mahasiswa.
     */
    public function uktSemesters()
    {
        return $this->hasMany(UktSemester::class, 'nim');
    }

    /**
     * Get the pembayaran UKT for the mahasiswa.
     */
    public function pembayaranUkt()
    {
        return $this->hasMany(PembayaranUktSemester::class, 'nim');
    }

    /**
     * Get the beasiswa that the mahasiswa has received.
     */
    public function beasiswa()
    {
        return $this->hasMany(PenerimaBeasiswa::class, 'nim');
    }

    /**
     * Get current class.
     */
    public function kelas()
    {
        return $this->hasOneThrough(
            Kelas::class,
            EnrollmentMahasiswa::class,
            'nim', // Foreign key on enrollment_mahasiswa table
            'id', // Foreign key on kelas table
            'nim', // Local key on mahasiswa table
            'id_kelas' // Local key on enrollment_mahasiswa table
        )
        ->orderByDesc('enrollment_mahasiswa.created_at')
        ->limit(1);
    }

    /**
     * Get current tingkat (level).
     */
    public function tingkat()
    {
        return $this->hasOneThrough(
            Tingkat::class,
            EnrollmentMahasiswa::class,
            'nim', // Foreign key on enrollment_mahasiswa table
            'id', // Foreign key on tingkat table
            'nim', // Local key on mahasiswa table
            'id_tingkat' // Local key on enrollment_mahasiswa table
        )
        ->orderByDesc('enrollment_mahasiswa.created_at')
        ->limit(1);
    }
}
