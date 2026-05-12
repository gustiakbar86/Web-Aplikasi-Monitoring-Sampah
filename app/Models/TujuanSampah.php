<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanSampah extends Model
{
    /** @use HasFactory<\Database\Factories\TujuanSampahFactory> */
    use HasFactory;
    
    protected $table = 'tujuan_sampahs';
    protected $primaryKey = 'id_tujuan';
    
    protected $fillable = [
        'kategori_tujuan',
        'nama_tujuan',
        'alamat',
        'status',
    ];

    public function sampahTerkelola()
    {
        return $this->hasMany(SampahTerkelola::class, 'id_tujuan', 'id_tujuan');
    }

    public function sampahDiserahkan()
    {
        return $this->hasMany(SampahDiserahkan::class, 'id_tujuan', 'id_tujuan');
    }
}
