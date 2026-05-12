<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampahTerkelola extends Model
{
    use HasFactory;

    protected $table = 'sampah_terkelolas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_user',
        'id_lokasi',
        'id_jenis',
        'jumlah_berat',
        'tgl',
        'foto_kelola',
        'alasan_edit',
    ];

    protected $casts = [
        'tgl' => 'date',
        'jumlah_berat' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lokasiAsal()
    {
        return $this->belongsTo(LokasiAsal::class, 'id_lokasi');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }
}