<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TujuanSampah;

class TujuanSampahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'kategori_tujuan' => 'sampah',
                'nama_tujuan' => 'TPA Pelabuhan',
                'alamat' => 'Jl. Pelabuhan No.1, Dekat Pelabuhan',
                'status' => 1,
            ],
            [
                'kategori_tujuan' => 'sampah',
                'nama_tujuan' => 'TPA Sekitar Pelabuhan',
                'alamat' => 'Jl. Dermaga Raya, Dekat Pelabuhan',
                'status' => 1,
            ],
            [
                'kategori_tujuan' => 'sampah',
                'nama_tujuan' => 'Bank Sampah Pelabuhan',
                'alamat' => 'Komplek Pelabuhan, Dekat Dermaga',
                'status' => 1,
            ],
            [
                'kategori_tujuan' => 'lb3',
                'nama_tujuan' => 'Pengelola Limbah Khusus Pelabuhan',
                'alamat' => 'Jl. Industri Pelabuhan, Dekat Pelabuhan',
                'status' => 1,
            ],
        ];

        foreach ($items as $it) {
            TujuanSampah::create($it);
        }
    }
}
