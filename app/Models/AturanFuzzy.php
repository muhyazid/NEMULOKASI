<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AturanFuzzy extends Model
{
     use HasFactory;
    protected $table = 'aturan_fuzzy';
    protected $fillable = [
        'kondisi',
        'hasil'
    ];
    protected $casts = [
        'kondisi' => 'array'
    ];
}
