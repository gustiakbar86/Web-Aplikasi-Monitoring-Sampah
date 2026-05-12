<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrator::create([
            'nama_admin' => 'Super Admin',
            'email_admin' => 'superadmin@sipesa.com',
            'password_admin' => 'password123',
            'role_admin' => 'super_admin',
        ]);

        Administrator::create([
            'nama_admin' => 'Admin',
            'email_admin' => 'admin@sipesa.com',
            'password_admin' => 'password123',
            'role_admin' => 'admin',
        ]);
    }
}
