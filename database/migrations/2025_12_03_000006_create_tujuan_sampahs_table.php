<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tujuan_sampahs', function (Blueprint $table) {
            $table->id('id_tujuan');
            $table->string('kategori_tujuan');
            $table->string('nama_tujuan');
            $table->text('alamat')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tujuan_sampahs');
    }
};
