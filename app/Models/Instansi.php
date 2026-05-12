<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansis';
    protected $primaryKey = 'id_instansi';

    protected $fillable = [
        'nama_instansi',
        'kode_instansi',
    ];

    /**
     * Relasi ke User (petugas)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_instansi', 'id_instansi');
    }

    /**
     * Relasi ke Sampah Terkelola
     */
    public function sampahTerkelolas()
    {
        return $this->hasManyThrough(
            SampahTerkelola::class,
            User::class,
            'id_instansi', // Foreign key on users table
            'id_user',      // Foreign key on sampah_terkelolas table
            'id_instansi',  // Local key on instansis table
            'id'            // Local key on users table
        );
    }

    /**
     * Relasi ke Sampah Diserahkan
     */
    public function sampahDiserahkans()
    {
        return $this->hasManyThrough(
            SampahDiserahkan::class,
            User::class,
            'id_instansi', // Foreign key on users table
            'id_user',      // Foreign key on sampah_diserahkans table
            'id_instansi',  // Local key on instansis table
            'id'            // Local key on users table
        );
    }
}
