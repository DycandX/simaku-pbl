<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama_lengkap',
        'alamat',
        'no_telepon',
        'foto_path'
    ];

    // Polymorphic relationship dengan User
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    // Relationship dengan EnrollmentMahasiswa
    public function enrollments()
    {
        return $this->hasMany(EnrollmentMahasiswa::class, 'id_mahasiswa');
    }

    // Get active enrollment
    public function activeEnrollment()
    {
        return $this->hasOne(EnrollmentMahasiswa::class, 'id_mahasiswa')
                    ->whereHas('tahunAkademik', function($query) {
                        $query->where('status', 'aktif');
                    });
    }

    // Check if mahasiswa already has user account
    public function hasUser()
    {
        return $this->user()->exists();
    }

    // Get current UKT
    public function getCurrentUkt()
    {
        return $this->activeEnrollment?->uktSemester()
                    ->where('status', 'aktif')
                    ->first();
    }
}