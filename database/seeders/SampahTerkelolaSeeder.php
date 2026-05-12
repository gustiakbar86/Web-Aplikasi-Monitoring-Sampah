<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SampahTerkelola;
use App\Models\User;
use App\Models\LokasiAsal;
use App\Models\Jenis;
use Carbon\Carbon;

class SampahTerkelolaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $lokasis = LokasiAsal::pluck('id_lokasi')->toArray();
        $jeniss = Jenis::pluck('id_jenis')->toArray();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            SampahTerkelola::create([
                'id_user' => $faker->randomElement($users),
                'id_lokasi' => $faker->randomElement($lokasis),
                'id_jenis' => $faker->randomElement($jeniss),
                'jumlah_berat' => $faker->numberBetween(5, 200),
                'tgl' => Carbon::now()->subDays($faker->numberBetween(0, 365))->toDateString(),
                'foto_kelola' => null,
            ]);
        }
    }
}
