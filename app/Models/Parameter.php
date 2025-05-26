<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
   use HasFactory;

    protected $fillable = ['nama_parameter'];

    public function himpunanFuzzies()
    {
        return $this->hasMany(HimpunanFuzzy::class);
    }
}
