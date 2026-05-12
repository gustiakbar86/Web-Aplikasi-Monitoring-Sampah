<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jenis;

class JenisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['Organik', 'Anorganik', 'Residu'];
        foreach ($items as $name) {
            Jenis::create([
                'nama_jenis' => $name,
            ]);
        }
    }
}
