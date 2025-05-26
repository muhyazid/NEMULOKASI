<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HimpunanFuzzy extends Model
{
    use HasFactory;

    protected $table = 'himpunan_fuzzies';

    protected $fillable = [
        'parameter_id',
        'nama_himpunan',
        'nilai_linguistik_view',
        'nilai_crisp_input',
        'mf_a',
        'mf_b',
        'mf_c',
    ];

    /**
     * Get the parameter that owns the himpunan fuzzy.
     */
    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}