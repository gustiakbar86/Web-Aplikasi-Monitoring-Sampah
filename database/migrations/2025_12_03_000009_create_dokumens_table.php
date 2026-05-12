<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('no_dokumen')->unique();
            $table->string('nama_dokumen');
            $table->string('file_dokumen');
            $table->string('instansi_kerjasama')->nullable();
            $table->boolean('berlaku')->default(true);
            $table->date('berakhir')->nullable();
            $table->text('keterangan_dokumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
