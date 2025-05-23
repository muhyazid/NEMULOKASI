<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterFuzzy extends Model
{
    use HasFactory;
    protected $table = 'parameter_fuzzy';
    protected $fillable = ['nama_parameter', 'nilai_fuzzy', 'nilai_crisp'];
}
