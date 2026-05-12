<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_instansi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Instansi
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi', 'id_instansi');
    }

    /**
     * Check if user is petugas (has instansi)
     */
    public function isPetugas(): bool
    {
        return $this->id_instansi !== null;
    }

    /**
     * Relasi ke Sampah Terkelola
     */
    public function sampahTerkelolas()
    {
        return $this->hasMany(SampahTerkelola::class, 'id_user');
    }

    /**
     * Relasi ke Sampah Diserahkan
     */
    public function sampahDiserahkans()
    {
        return $this->hasMany(SampahDiserahkan::class, 'id_user');
    }
}
