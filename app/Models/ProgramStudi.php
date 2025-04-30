<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'program_studi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_prodi',
        'id_fakultas',
    ];

    /**
     * Get the fakultas that owns the program studi.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }

    /**
     * Get the kelas for the program studi.
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_prodi');
    }
}