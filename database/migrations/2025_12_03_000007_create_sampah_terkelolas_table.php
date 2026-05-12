<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sampah_terkelolas', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_lokasi')->constrained('lokasi_asals', 'id_lokasi')->onDelete('cascade');
            $table->foreignId('id_jenis')->constrained('jenis', 'id_jenis')->onDelete('cascade');
            $table->decimal('jumlah_berat', 10, 2);
            $table->date('tgl');
            $table->string('foto_kelola')->nullable();
            $table->text('alasan_edit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sampah_terkelolas');
    }
};
