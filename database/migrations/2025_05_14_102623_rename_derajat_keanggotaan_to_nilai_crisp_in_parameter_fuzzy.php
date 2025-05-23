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
        Schema::table('parameter_fuzzy', function (Blueprint $table) {
            $table->renameColumn('derajat_keanggotaan', 'nilai_crisp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parameter_fuzzy', function (Blueprint $table) {
            $table->renameColumn('nilai_crisp', 'derajat_keanggotaan');
        });
    }
};
