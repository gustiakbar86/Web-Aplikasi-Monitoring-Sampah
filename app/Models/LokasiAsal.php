<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiAsal extends Model
{
    /** @use HasFactory<\Database\Factories\LokasiAsalFactory> */
    use HasFactory;

    protected $table = 'lokasi_asals';
    protected $primaryKey = 'id_lokasi';
    protected $fillable = [
        'nama_lokasi',
    ];
}
