<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tingkat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_tingkat',
        'deskripsi',
    ];

    /**
     * Get the enrollments for the tingkat.
     */
    public function enrollments()
    {
        return $this->hasMany(EnrollmentMahasiswa::class, 'id_tingkat');
    }

    /**
     * Get the mahasiswa in this tingkat.
     */
    public function mahasiswa()
    {
        return $this->hasManyThrough(
            Mahasiswa::class,
            EnrollmentMahasiswa::class,
            'id_tingkat', // Foreign key on enrollment_mahasiswa table
            'nim', // Foreign key on mahasiswa table
            'id', // Local key on tingkat table
            'nim' // Local key on enrollment_mahasiswa table
        );
    }
}