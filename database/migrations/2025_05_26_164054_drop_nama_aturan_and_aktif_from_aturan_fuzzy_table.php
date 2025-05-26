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
        Schema::table('aturan_fuzzy', function (Blueprint $table) {
            //
            if (Schema::hasColumn('aturan_fuzzy', 'nama_aturan')) {
                // Jika nama_aturan memiliki unique constraint, hapus dulu constraint-nya
                // $table->dropUnique('nama_aturan_unique_constraint_name'); // Ganti dengan nama constraint Anda jika ada
                $table->dropColumn('nama_aturan');
            }
            if (Schema::hasColumn('aturan_fuzzy', 'aktif')) {
                $table->dropColumn('aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aturan_fuzzy', function (Blueprint $table) {
            //
             // Tambahkan kembali kolom nama_aturan. Sesuaikan jika sebelumnya ada unique constraint atau not null.
            // Untuk keamanan rollback, kita buat nullable atau beri default jika memungkinkan.
            if (!Schema::hasColumn('aturan_fuzzy', 'nama_aturan')) {
                // Anda mungkin perlu menyesuaikan ini jika 'nama_aturan' sebelumnya 'unique' dan 'not nullable'.
                // Jika 'unique', Anda perlu memberinya nilai unik saat rollback atau membuatnya nullable.
                $table->string('nama_aturan')->nullable()->after('id'); 
            }
            if (!Schema::hasColumn('aturan_fuzzy', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('hasil'); // Asumsikan defaultnya aktif
            }
        });
    }
};
