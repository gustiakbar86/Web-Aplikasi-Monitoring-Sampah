<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Petugas1
        User::create([
            'name' => 'Petugas1',
            'email' => 'petugas1@example.com',
            'password' => Hash::make('password'),
        ]);

        // Petugas4
        User::create([
            'name' => 'Petugas4',
            'email' => 'petugas4@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
