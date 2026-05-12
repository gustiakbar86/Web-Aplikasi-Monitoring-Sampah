<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SampahDiserahkan;
use App\Models\User;
use App\Models\LokasiAsal;
use App\Models\Jenis;
use App\Models\TujuanSampah;
use Carbon\Carbon;

class SampahDiserahkanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $lokasis = LokasiAsal::pluck('id_lokasi')->toArray();
        $jeniss = Jenis::pluck('id_jenis')->toArray();
        $tujuans = TujuanSampah::pluck('id_tujuan')->toArray();
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            SampahDiserahkan::create([
                'id_user' => $faker->randomElement($users),
                'id_lokasi' => $faker->randomElement($lokasis),
                'id_jenis' => $faker->randomElement($jeniss),
                'jumlah_berat' => $faker->numberBetween(5, 300),
                'tgl_diserahkan' => Carbon::now()->subDays($faker->numberBetween(0, 365))->toDateString(),
                'id_tujuan' => $faker->randomElement($tujuans),
                'foto_diserahkan' => null,
            ]);
        }
    }
}
