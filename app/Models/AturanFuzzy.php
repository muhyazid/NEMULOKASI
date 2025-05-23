<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AturanFuzzy extends Model
{
     use HasFactory;
    
    protected $table = 'aturan_fuzzy';
    
    protected $fillable = [
        'nama_aturan',
        'kondisi',
        'hasil',
        'aktif'
    ];
    
    protected $casts = [
        'kondisi' => 'array',
        'aktif' => 'boolean'
    ];
    
    // Scope untuk mengambil aturan yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
