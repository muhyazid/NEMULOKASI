<?php

namespace App\Http\Controllers;

use App\Models\ParameterFuzzy;
use Illuminate\Http\Request;

class ParameterFuzzyController extends Controller
{
    public function tampilkanForm()
    {
        return view('form_parameter_fuzzy');
    }
    public function simpanParameterFuzzy(Request $request)
    {
        // Validasi data dari form
        $data = $request->validate([
            'nama_parameter' => 'required|string',
            'nilai_fuzzy' => 'required|string',
            'nilai_crisp' => 'required|numeric', 
        ]);
        // Simpan nilai parameter fuzzy ke dalam tabel
        ParameterFuzzy::create([
            'nama_parameter' => $data['nama_parameter'],
            'nilai_fuzzy' => $data['nilai_fuzzy'],
            'nilai_crisp' => $data['nilai_crisp'],
        ]);
        // Redirect atau tampilkan pesan sukses
        return redirect()->route('parameter-fuzzy.form')->with('success', 'Parameter fuzzy berhasil disimpan!');
    }
}