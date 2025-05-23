<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aturan_fuzzy', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aturan'); // Nama/deskripsi aturan
            $table->json('kondisi'); // Menyimpan kondisi IF dalam format JSON
            $table->string('hasil'); // Hasil THEN (sangat_layak, layak, tidak_layak)
            $table->boolean('aktif')->default(true); // Status aktif/non-aktif aturan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_fuzzy');
    }
};
