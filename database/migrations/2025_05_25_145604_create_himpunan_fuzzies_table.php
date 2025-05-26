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
        Schema::create('himpunan_fuzzies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_id')->constrained()->onDelete('cascade');
            $table->string('nama_himpunan'); // <-- PASTIKAN INI ADA DAN BENAR
            $table->string('nilai_linguistik_view');
            $table->float('nilai_crisp_input');
            $table->float('mf_a');
            $table->float('mf_b');
            $table->float('mf_c');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('himpunan_fuzzies');
    }
};
