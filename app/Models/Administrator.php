<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'administrators';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_admin',
        'email_admin',
        'password_admin',
        'role_admin',
    ];

    protected $hidden = [
        'password_admin',
    ];

    protected function casts(): array
    {
        return [
            'password_admin' => 'hashed',
        ];
    }

    /**
     * Override untuk authentication
     */
    public function getAuthPassword()
    {
        return $this->password_admin;
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_admin === 'super_admin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role_admin === 'admin';
    }
}
