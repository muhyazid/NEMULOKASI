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
        Schema::table('tempat_bisnis', function (Blueprint $table) {
            //
            $table->text('deskripsi_lokasi')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tempat_bisnis', function (Blueprint $table) {
                // Hapus kolom deskripsi_lokasi jika migration di-rollback
                $table->dropColumn('deskripsi_lokasi');
        });
    }
};
