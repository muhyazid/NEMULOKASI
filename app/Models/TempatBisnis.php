<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatBisnis extends Model
{
    use HasFactory;
    protected $table = 'tempat_bisnis';
    protected $fillable = [
        'nama_tempat',
        'latitude',
        'longitude',
        'deskripsi_lokasi',
    ];
}