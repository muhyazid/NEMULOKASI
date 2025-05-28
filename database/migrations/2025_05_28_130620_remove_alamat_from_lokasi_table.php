<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Mengecek apakah tabel 'lokasi' ada sebelum mencoba memodifikasinya
        if (Schema::hasTable('lokasi')) {
            Schema::table('lokasi', function (Blueprint $table) {
                // Mengecek apakah kolom 'alamat' ada sebelum mencoba menghapusnya
                if (Schema::hasColumn('lokasi', 'alamat')) {
                    $table->dropColumn('alamat'); // Hapus kolom 'alamat'
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Mengecek apakah tabel 'lokasi' ada sebelum mencoba memodifikasinya
        if (Schema::hasTable('lokasi')) {
            Schema::table('lokasi', function (Blueprint $table) {
                // Mengecek apakah kolom 'alamat' TIDAK ada sebelum mencoba menambahkannya kembali
                if (!Schema::hasColumn('lokasi', 'alamat')) {
                    // Tambahkan kembali kolom 'alamat'
                    // Sesuaikan tipe data (text, string, dll.) dan posisinya (->after('nama')) 
                    // jika diperlukan, agar sesuai dengan struktur sebelumnya.
                    // Di sini kita asumsikan 'alamat' adalah TEXT dan bisa NULL.
                    $table->text('alamat')->nullable()->after('nama'); 
                }
            });
        }
    }
};