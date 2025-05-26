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
       Schema::table('lokasi', function (Blueprint $table) {
             // Pastikan kolom ini belum ada sebelum mencoba menambahkannya
            if (!Schema::hasColumn('lokasi', 'kelayakan')) {
                $table->string('kelayakan')->nullable()->after('skor_lokasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn('kelayakan');
        });
    }
};
