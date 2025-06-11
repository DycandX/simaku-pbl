<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'jabatan',
        'unit_kerja'
    ];

    // Polymorphic relationship dengan User
    // public function user()
    // {
    //     return $this->morphOne(User::class, 'userable');
    // }

    // Relationship dengan PengajuanCicilan yang di-approve
    public function approvedPengajuan()
    {
        return $this->hasMany(PengajuanCicilan::class, 'approved_by');
    }

    public function verifikasiPembayaran()
    {
        return $this->hasMany(DetailPembayaran::class, 'verified_by');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'staff_id');
    }
    
    // Check if staff already has user account
    public function hasUser()
    {
        return $this->user()->exists();
    }
}