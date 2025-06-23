<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'fakultas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_fakultas',
    ];

    /**
     * Get the program studi for the fakultas.
     */
    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class, 'id_fakultas');
    }
}
