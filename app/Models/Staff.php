<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'nip',
        'nama_lengkap',
        'jabatan',
        'unit_kerja',
    ];

    /**
     * Get the user that owns the staff.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the payment verifications done by this staff.
     */
    public function verifiedPayments()
    {
        return $this->hasMany(DetailPembayaran::class, 'verified_by');
    }

    /**
     * Get the scholarships created by this staff.
     */
    public function createdScholarships()
    {
        return $this->hasMany(PenerimaBeasiswa::class, 'created_by');
    }
}