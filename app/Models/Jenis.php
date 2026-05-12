<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    /** @use HasFactory<\Database\Factories\JenisFactory> */
    use HasFactory;
    protected $table = 'jenis';
    protected $primaryKey = 'id_jenis';
    
    protected $fillable = [
        'kategori_jenis',
        'nama_jenis',
    ];

    /**
     * Relasi ke Sampah Terkelola
     */
    public function sampahTerkelolas()
    {
        return $this->hasMany(SampahTerkelola::class, 'id_jenis', 'id_jenis');
    }

    /**
     * Relasi ke Sampah Diserahkan
     */
    public function sampahDiserahkans()
    {
        return $this->hasMany(SampahDiserahkan::class, 'id_jenis', 'id_jenis');
    }
}
