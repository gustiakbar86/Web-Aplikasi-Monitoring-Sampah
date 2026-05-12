<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;
    
    protected $table = 'dokumens';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_user',
        'no_dokumen',
        'nama_dokumen',
        'file_dokumen',
        'instansi_kerjasama',
        'berlaku',
        'berakhir',
        'keterangan_dokumen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
