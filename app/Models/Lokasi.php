<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = [
        'nama', 'alamat', 'aksesibilitas', 'visibilitas', 'daya_beli',
        'persaingan', 'infrastruktur', 'lingkungan_sekitar', 'parkir', 'skor_lokasi'
    ];
}
