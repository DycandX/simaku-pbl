<?php
// Model User
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'role',
        'mahasiswa_id', 'staff_id', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    // Relationships
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Helper methods
    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Get profile data
    public function getProfileAttribute()
    {
        if ($this->mahasiswa_id) {
            return $this->mahasiswa;
        } elseif ($this->staff_id) {
            return $this->staff;
        }
        return null;
    }

    // Get display name
    public function getDisplayNameAttribute()
    {
        $profile = $this->profile;
        return $profile ? $profile->nama_lengkap : $this->username;
    }

    // Create user dari mahasiswa
    public static function createFromMahasiswa(Mahasiswa $mahasiswa, $email, $password)
    {
        return self::create([
            'username' => $mahasiswa->nim,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'mahasiswa',
            'mahasiswa_id' => $mahasiswa->id,
            'is_active' => true
        ]);
    }

    // Create user dari staff
    public static function createFromStaff(Staff $staff, $email, $password)
    {
        return self::create([
            'username' => $staff->nip,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'staff',
            'staff_id' => $staff->id,
            'is_active' => true
        ]);
    }
}
