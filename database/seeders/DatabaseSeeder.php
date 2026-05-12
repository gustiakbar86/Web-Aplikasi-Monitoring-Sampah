<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\JenisTableSeeder;
use Database\Seeders\LokasiAsalTableSeeder;
use Database\Seeders\TujuanSampahTableSeeder;
use Database\Seeders\SampahTerkelolaSeeder;
use Database\Seeders\SampahDiserahkanSeeder;
use Database\Seeders\DokumenTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin & Users
        $this->call(AdministratorSeeder::class);
        $this->call(InstansiSeeder::class);
        $this->call(UsersTableSeeder::class);
        
        // Master data
        $this->call(JenisTableSeeder::class);
        $this->call(LokasiAsalTableSeeder::class);
        $this->call(TujuanSampahTableSeeder::class);

        // Dokumen
        $this->call(DokumenTableSeeder::class);

        // transaction data
        $this->call(SampahTerkelolaSeeder::class);
        $this->call(SampahDiserahkanSeeder::class);
    }
}
