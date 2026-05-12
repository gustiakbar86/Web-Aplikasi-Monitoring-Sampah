<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokumen;
use App\Models\User;
use Carbon\Carbon;

class DokumenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        $now = Carbon::now();

        $samples = [
            [
                'judul' => 'Rekap Pengelolaan Sampah September 2025',
                'file' => 'dokumen/rekap-sep-2025.pdf',
                'keterangan' => 'Laporan rekap pengelolaan sampah bulan September 2025',
            ],
            [
                'judul' => 'SOP Pengelolaan Sampah',
                'file' => 'dokumen/sop-pengelolaan.pdf',
                'keterangan' => 'Standar Operasional Prosedur',
            ],
            [
                'judul' => 'Kerjasama Pengelolaan Limbah',
                'file' => 'dokumen/kerjasama-limbah.pdf',
                'keterangan' => 'Perjanjian kerjasama dengan pihak ketiga',
            ],
        ];

        foreach ($samples as $idx => $s) {
            Dokumen::create([
                'id_user' => $user->id,
                'no_dokumen' => 'DOK-' . ($idx + 1) . '-' . $now->format('Ymd'),
                'nama_dokumen' => $s['judul'],
                'file_dokumen' => $s['file'],
                'instansi_kerjasama' => 'Pelindo Subregional Banjarmasin',
                'berlaku' => 1,
                'berakhir' => $now->addYear()->toDateString(),
                'keterangan_dokumen' => $s['keterangan'],
            ]);
        }
    }
}
