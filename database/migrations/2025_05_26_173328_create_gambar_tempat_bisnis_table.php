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
        Schema::create('gambar_tempat_bisnis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempat_bisnis_id')->constrained('tempat_bisnis')->onDelete('cascade');
            $table->string('path_gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_tempat_bisnis');
    }
};
