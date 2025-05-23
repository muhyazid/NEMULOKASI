<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('aksesibilitas');
            $table->string('visibilitas');
            $table->string('daya_beli');
            $table->string('persaingan');
            $table->string('infrastruktur');
            $table->string('lingkungan_sekitar');
            $table->string('parkir');
            $table->float('skor_lokasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lokasi');
    }
};
