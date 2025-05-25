<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParameterFuzzy;

class ParameterFuzzyController extends Controller
{
    // Method untuk form input cepat
    public function tampilkanForm()
    {
        // Ambil semua parameter unik dari database
        $availableParameters = $this->getAvailableParameters();
        
        return view('form_parameter_fuzzy', compact('availableParameters'));
    }
    
    public function simpanParameterFuzzy(Request $request)
    {
        $data = $request->validate([
            'nama_parameter' => 'required|string',
            'nilai_fuzzy' => 'required|string',
            'nilai_crisp' => 'required|numeric|min:0|max:10', 
        ]);
        
        // Cek duplikasi kombinasi nama_parameter dan nilai_fuzzy
        $existing = ParameterFuzzy::where('nama_parameter', $data['nama_parameter'])
                                 ->where('nilai_fuzzy', strtolower($data['nilai_fuzzy']))
                                 ->first();
        
        if ($existing) {
            return back()->withErrors(['nilai_fuzzy' => 'Kombinasi parameter dan nilai fuzzy sudah ada.'])
                        ->withInput();
        }
        
        ParameterFuzzy::create([
            'nama_parameter' => $data['nama_parameter'],
            'nilai_fuzzy' => strtolower($data['nilai_fuzzy']),
            'nilai_crisp' => $data['nilai_crisp'],
        ]);
        
        return redirect()->route('parameter-fuzzy.form')->with('success', 'Parameter fuzzy berhasil disimpan!');
    }
    
    // Method CRUD lengkap
    public function index()
    {
        $parameterFuzzy = ParameterFuzzy::orderBy('nama_parameter')
                                       ->orderBy('nilai_crisp')
                                       ->get();
        
        // Group by parameter untuk tampilan yang lebih rapi
        $groupedParameters = $parameterFuzzy->groupBy('nama_parameter');
        
        return view('parameter_fuzzy.index', compact('parameterFuzzy', 'groupedParameters'));
    }
    
    public function create()
    {
        $availableParameters = $this->getAvailableParameters();
        $suggestedParameters = $this->getSuggestedParameters();
        
        return view('parameter_fuzzy.create', compact('availableParameters', 'suggestedParameters'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_parameter' => 'required|string|max:255',
            'nilai_fuzzy' => 'required|string|max:255',
            'nilai_crisp' => 'required|numeric|min:0|max:10',
        ]);
        
        // Cek duplikasi kombinasi nama_parameter dan nilai_fuzzy
        $existing = ParameterFuzzy::where('nama_parameter', $request->nama_parameter)
                                 ->where('nilai_fuzzy', strtolower($request->nilai_fuzzy))
                                 ->first();
        
        if ($existing) {
            return back()->withErrors(['nilai_fuzzy' => 'Kombinasi parameter dan nilai fuzzy sudah ada.'])
                        ->withInput();
        }
        
        ParameterFuzzy::create([
            'nama_parameter' => $request->nama_parameter,
            'nilai_fuzzy' => strtolower($request->nilai_fuzzy),
            'nilai_crisp' => $request->nilai_crisp
        ]);
        
        return redirect()->route('parameter-fuzzy.index')
                        ->with('success', 'Parameter fuzzy berhasil dibuat!');
    }
    
    public function show(ParameterFuzzy $parameterFuzzy)
    {
        return view('parameter_fuzzy.show', compact('parameterFuzzy'));
    }
    
    public function edit(ParameterFuzzy $parameterFuzzy)
    {
        $availableParameters = $this->getAvailableParameters();
        $suggestedParameters = $this->getSuggestedParameters();
        
        return view('parameter_fuzzy.edit', compact('parameterFuzzy', 'availableParameters', 'suggestedParameters'));
    }
    
    public function update(Request $request, ParameterFuzzy $parameterFuzzy)
    {
        $request->validate([
            'nama_parameter' => 'required|string|max:255',
            'nilai_fuzzy' => 'required|string|max:255',
            'nilai_crisp' => 'required|numeric|min:0|max:10',
        ]);
        
        // Cek duplikasi kombinasi nama_parameter dan nilai_fuzzy (kecuali record ini sendiri)
        $existing = ParameterFuzzy::where('nama_parameter', $request->nama_parameter)
                                 ->where('nilai_fuzzy', strtolower($request->nilai_fuzzy))
                                 ->where('id', '!=', $parameterFuzzy->id)
                                 ->first();
        
        if ($existing) {
            return back()->withErrors(['nilai_fuzzy' => 'Kombinasi parameter dan nilai fuzzy sudah ada.'])
                        ->withInput();
        }
        
        $parameterFuzzy->update([
            'nama_parameter' => $request->nama_parameter,
            'nilai_fuzzy' => strtolower($request->nilai_fuzzy),
            'nilai_crisp' => $request->nilai_crisp
        ]);
        
        return redirect()->route('parameter-fuzzy.index')
                        ->with('success', 'Parameter fuzzy berhasil diupdate!');
    }
    
    public function destroy(ParameterFuzzy $parameterFuzzy)
    {
        $parameterFuzzy->delete();
        
        return redirect()->route('parameter-fuzzy.index')
                        ->with('success', 'Parameter fuzzy berhasil dihapus!');
    }
    
    // Method untuk mendapatkan parameter yang tersedia
    private function getAvailableParameters()
    {
        return ParameterFuzzy::distinct()->pluck('nama_parameter')->toArray();
    }
    
    // Method untuk mendapatkan saran parameter standar
    private function getSuggestedParameters()
    {
        return [
            'aksesibilitas' => [
                'sangat mudah' => 8,
                'sedang' => 5,
                'tidak mudah' => 2
            ],
            'visibilitas' => [
                'sangat terlihat' => 8,
                'terlihat sebagian' => 5,
                'tidak terlihat' => 2
            ],
            'daya_beli' => [
                'tinggi' => 8,
                'menengah' => 5,
                'rendah' => 2
            ],
            'persaingan' => [
                'rendah' => 8,
                'sedang' => 5,
                'tinggi' => 2
            ],
            'infrastruktur' => [
                'lengkap' => 8,
                'cukup' => 5,
                'tidak lengkap' => 2
            ],
            'lingkungan_sekitar' => [
                'sangat mendukung' => 8,
                'netral' => 5,
                'tidak mendukung' => 2
            ],
            'parkir' => [
                'luas' => 8,
                'sedang' => 5,
                'sempit' => 2
            ]
        ];
    }
    
    // Method untuk bulk insert parameter standar
    public function insertStandardParameters()
    {
        $standardParameters = $this->getSuggestedParameters();
        $insertedCount = 0;
        
        foreach ($standardParameters as $parameter => $values) {
            foreach ($values as $nilaiLinguistik => $nilaiCrisp) {
                $existing = ParameterFuzzy::where('nama_parameter', $parameter)
                                         ->where('nilai_fuzzy', $nilaiLinguistik)
                                         ->exists();
                
                if (!$existing) {
                    ParameterFuzzy::create([
                        'nama_parameter' => $parameter,
                        'nilai_fuzzy' => $nilaiLinguistik,
                        'nilai_crisp' => $nilaiCrisp
                    ]);
                    $insertedCount++;
                }
            }
        }
        
        return redirect()->route('parameter-fuzzy.index')
                        ->with('success', "Berhasil menambahkan $insertedCount parameter standar!");
    }
    
    // API endpoint untuk mendapatkan nilai fuzzy berdasarkan parameter
    public function getNilaiFuzzyByParameter($parameter)
    {
        $values = ParameterFuzzy::where('nama_parameter', $parameter)
                               ->orderBy('nilai_crisp')
                               ->get(['nilai_fuzzy', 'nilai_crisp']);
        
        return response()->json($values);
    }
    
    // Method untuk validasi konsistensi parameter
    public function validateParameterConsistency()
    {
        $parameters = $this->getAvailableParameters();
        $inconsistencies = [];
        
        foreach ($parameters as $parameter) {
            $count = ParameterFuzzy::where('nama_parameter', $parameter)->count();
            
            if ($count < 3) {
                $inconsistencies[] = "Parameter '$parameter' memiliki kurang dari 3 nilai linguistik ($count nilai)";
            }
            
            // Cek apakah ada nilai crisp yang sama untuk parameter yang sama
            $duplicateCrisp = ParameterFuzzy::where('nama_parameter', $parameter)
                                           ->groupBy('nilai_crisp')
                                           ->havingRaw('COUNT(*) > 1')
                                           ->pluck('nilai_crisp');
            
            if ($duplicateCrisp->isNotEmpty()) {
                $inconsistencies[] = "Parameter '$parameter' memiliki nilai crisp yang sama: " . $duplicateCrisp->implode(', ');
            }
        }
        
        return $inconsistencies;
    }
}