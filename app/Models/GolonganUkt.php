<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GolonganUkt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'golongan_ukt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level',
        'nominal',
        'deskripsi',
        'tahun_berlaku',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nominal' => 'decimal:2',
        'tahun_berlaku' => 'integer',
    ];

    /**
     * Get the UKT semesters for the golongan UKT.
     */
    public function uktSemesters()
    {
        return $this->hasMany(UktSemester::class, 'id_golongan_ukt');
    }
}