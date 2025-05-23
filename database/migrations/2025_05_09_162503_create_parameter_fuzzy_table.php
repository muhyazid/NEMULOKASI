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
        Schema::create('parameter_fuzzy', function (Blueprint $table) {
            $table->id();
            $table->string('nama_parameter');
            $table->string('nilai_fuzzy');
            $table->float('derajat_keanggotaan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parameter_fuzzy');
    }
};
