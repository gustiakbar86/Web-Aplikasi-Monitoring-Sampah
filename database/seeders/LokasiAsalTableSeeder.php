<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LokasiAsal;

class LokasiAsalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            'Area Kantor',
            'Area Tempat Parkir/Taman/Jalan',
            'Area Ruang Tunggu',
            'Area Tempat Makan',
            'Sampah Kapal',
            'Area Lain',
        ];

        foreach ($areas as $area) {
            LokasiAsal::create([
                'nama_lokasi' => $area,
            ]);
        }
    }
}
