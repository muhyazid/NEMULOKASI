<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sistem_config', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // contoh: 'fuzzy_boundaries', 'system_settings'
            $table->string('nama')->nullable(); // nama yang user-friendly
            $table->text('value'); // JSON value untuk fleksibilitas
            $table->string('type')->default('json'); // json, string, number, boolean
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        // Insert default fuzzy boundaries
        DB::table('sistem_config')->insert([
            [
                'key' => 'fuzzy_boundaries',
                'nama' => 'Batas Fungsi Keanggotaan Fuzzy',
                'value' => json_encode([
                    'rendah' => ['min' => 0, 'peak' => 2, 'max' => 5],
                    'sedang' => ['min' => 2, 'peak' => 5, 'max' => 8],
                    'tinggi' => ['min' => 5, 'peak' => 8, 'max' => 10]
                ]),
                'type' => 'json',
                'description' => 'Batas untuk fungsi keanggotaan fuzzy (rendah, sedang, tinggi)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'default_crisp_values',
                'nama' => 'Nilai Crisp Default',
                'value' => json_encode([
                    'rendah' => 2,
                    'sedang' => 5,
                    'tinggi' => 8
                ]),
                'type' => 'json',
                'description' => 'Nilai crisp default untuk setiap kategori linguistik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'threshold_kelayakan',
                'nama' => 'Threshold Kelayakan Lokasi',
                'value' => json_encode([
                    'sangat_layak' => 70,
                    'layak' => 50,
                    'tidak_layak' => 0
                ]),
                'type' => 'json',
                'description' => 'Batas skor untuk menentukan kelayakan lokasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistem_config');
    }
};
