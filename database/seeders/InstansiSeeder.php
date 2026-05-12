<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instansis = [
            [
                'nama_instansi' => 'Pelindo Terminal Petikemas',
                'kode_instansi' => 'PTP001',
            ],
            [
                'nama_instansi' => 'Pelindo Terminal Multipurpose',
                'kode_instansi' => 'PTM002',
            ],
            [
                'nama_instansi' => 'Pelindo Terminal Penumpang',
                'kode_instansi' => 'PTP003',
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::create($instansi);
        }
    }
}
