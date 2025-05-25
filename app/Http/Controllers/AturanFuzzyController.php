<?php

namespace App\Http\Controllers;

use App\Models\AturanFuzzy;
use App\Models\ParameterFuzzy;
use Illuminate\Http\Request;

class AturanFuzzyController extends Controller
{
    public function index()
    {
        $aturanFuzzy = AturanFuzzy::orderBy('created_at', 'desc')->get();
        return view('aturan_fuzzy.index', compact('aturanFuzzy'));
    }
    
    public function create()
    {
        $parameters = ['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 
                      'infrastruktur', 'lingkungan_sekitar', 'parkir'];
        
        // Untuk setiap parameter, ambil nilai fuzzy yang tersedia
        $parameterOptions = [];
        foreach ($parameters as $param) {
            $parameterOptions[$param] = ParameterFuzzy::where('nama_parameter', $param)
                                                     ->orderBy('nilai_crisp')
                                                     ->pluck('nilai_fuzzy', 'nilai_fuzzy')
                                                     ->toArray();
        }
        
        return view('aturan_fuzzy.create', compact('parameters', 'parameterOptions'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_aturan' => 'required|string|max:255',
            'kondisi' => 'required|array',
            'hasil' => 'required|in:sangat_layak,layak,tidak_layak',
            'aktif' => 'boolean'
        ]);
        
        AturanFuzzy::create([
            'nama_aturan' => $request->nama_aturan,
            'kondisi' => $request->kondisi,
            'hasil' => $request->hasil,
            'aktif' => $request->has('aktif')
        ]);
        
        return redirect()->route('aturan-fuzzy.index')
                        ->with('success', 'Aturan fuzzy berhasil dibuat!');
    }
    
    public function show(AturanFuzzy $aturanFuzzy)
    {
        return view('aturan_fuzzy.show', compact('aturanFuzzy'));
    }
    
    public function edit(AturanFuzzy $aturanFuzzy)
    {
        $parameters = ['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 
                      'infrastruktur', 'lingkungan_sekitar', 'parkir'];
        
        $parameterOptions = [];
        foreach ($parameters as $param) {
            $parameterOptions[$param] = ParameterFuzzy::where('nama_parameter', $param)
                                                     ->orderBy('nilai_crisp')
                                                     ->pluck('nilai_fuzzy', 'nilai_fuzzy')
                                                     ->toArray();
        }
        
        return view('aturan_fuzzy.edit', compact('aturanFuzzy', 'parameters', 'parameterOptions'));
    }
    
    public function update(Request $request, AturanFuzzy $aturanFuzzy)
    {
        $request->validate([
            'nama_aturan' => 'required|string|max:255',
            'kondisi' => 'required|array',
            'hasil' => 'required|in:sangat_layak,layak,tidak_layak',
            'aktif' => 'boolean'
        ]);
        
        $aturanFuzzy->update([
            'nama_aturan' => $request->nama_aturan,
            'kondisi' => $request->kondisi,
            'hasil' => $request->hasil,
            'aktif' => $request->has('aktif')
        ]);
        
        return redirect()->route('aturan-fuzzy.index')
                        ->with('success', 'Aturan fuzzy berhasil diupdate!');
    }
    
    public function destroy(AturanFuzzy $aturanFuzzy)
    {
        $aturanFuzzy->delete();
        
        return redirect()->route('aturan-fuzzy.index')
                        ->with('success', 'Aturan fuzzy berhasil dihapus!');
    }
}