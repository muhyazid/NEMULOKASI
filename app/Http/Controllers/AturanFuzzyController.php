<?php

namespace App\Http\Controllers;

use App\Models\AturanFuzzy;
use App\Models\Parameter;
// use App\Models\HimpunanFuzzy; // Tidak secara eksplisit dipanggil di sini karena sudah melalui relasi Parameter
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Aktifkan jika Anda ingin menggunakan Log
use Illuminate\Support\Str;

class AturanFuzzyController extends Controller
{
    /**
     * Mengambil data parameter dan himpunan fuzzynya untuk digunakan di form create dan edit.
     */
    private function getParameterDataForForm()
    {
        return Parameter::with('himpunanFuzzies')->orderBy('id')->get()->map(function ($param) {
            return [
                'id' => $param->id,
                'nama_parameter' => $param->nama_parameter, // e.g., 'aksesibilitas'
                'display_name' => Str::ucfirst(str_replace('_', ' ', $param->nama_parameter)), // e.g., 'Aksesibilitas'
                'himpunan_fuzzies' => $param->himpunanFuzzies->map(function ($hf) {
                    return [
                        'value_to_save' => $hf->nama_himpunan,        // Ini yang disimpan (e.g., "Rendah")
                        'text_to_display'  => $hf->nilai_linguistik_view // Ini yang ditampilkan (e.g., "Sangat Sulit")
                    ];
                })->toArray()
            ];
        });
    }

    /**
     * Menampilkan daftar semua aturan fuzzy.
     */
    public function index()
    {
        $allDBParameters = Parameter::with('himpunanFuzzies')->orderBy('id')->get();

        // Untuk header tabel di view index
        $parameterDisplayNames = $allDBParameters->map(function ($param) {
            return Str::ucfirst(str_replace('_', ' ', $param->nama_parameter));
        })->toArray();

        // Untuk mapping nama_himpunan ke nilai_linguistik_view di tubuh tabel
        $linguisticViewMap = [];
        foreach ($allDBParameters as $param) {
            $linguisticViewMap[$param->nama_parameter] = $param->himpunanFuzzies
                ->pluck('nilai_linguistik_view', 'nama_himpunan')
                ->toArray();
        }

        $aturans = AturanFuzzy::orderBy('id')->get(); // Urutkan berdasarkan ID

        return view('aturan_fuzzy.index', compact('aturans', 'parameterDisplayNames', 'linguisticViewMap'));
    }

    /**
     * Menampilkan form untuk membuat aturan fuzzy baru.
     */
    public function create()
    {
        $allParameters = $this->getParameterDataForForm();
        $outputOptions = ['Tidak Layak', 'Layak']; // Output tetap 'Tidak Layak' dan 'Layak'

        // Tidak ada lagi $namaAturanOtomatis karena fieldnya dihilangkan
        return view('aturan_fuzzy.create', compact('allParameters', 'outputOptions'));
    }

    /**
     * Menyimpan aturan fuzzy baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi dasar, nama_aturan sudah dihilangkan
        $request->validate([
            'hasil' => 'required|string',
            // Validasi untuk setiap parameter bisa ditambahkan di sini jika semua wajib diisi
        ]);

        $allParametersFromDB = Parameter::pluck('nama_parameter')->toArray();
        $kondisiFormatted = [];

        foreach ($allParametersFromDB as $paramName) {
            // $paramName adalah nama_parameter dari DB (e.g., 'aksesibilitas')
            // Input dari form akan memiliki name yang sama ($request->aksesibilitas, dll.)
            if ($request->has($paramName) && !empty($request->input($paramName))) {
                // $request->input($paramName) akan berisi 'value_to_save' dari option,
                // yaitu nama_himpunan (e.g., "Rendah", "Sedang", "Tinggi")
                $kondisiFormatted[$paramName] = $request->input($paramName);
            }
        }
        
        // Opsional: Memastikan minimal satu kondisi dipilih.
        // Jika semua parameter ditampilkan dan user bisa memilih "-- Tidak Digunakan --",
        // Anda mungkin ingin memastikan minimal ada satu parameter yang dipilih.
        // if (empty($kondisiFormatted)) {
        //     return back()->with('error', 'Minimal satu kondisi parameter harus dipilih.')->withInput();
        // }
        
        AturanFuzzy::create([
            // 'nama_aturan' field dihilangkan
            'kondisi' => $kondisiFormatted,
            'hasil' => $request->hasil,
            // 'aktif' field dihilangkan (jika kolom di DB di-set default true atau nullable)
        ]);

        return redirect()->route('aturan-fuzzy.index')->with('success', 'Aturan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit aturan fuzzy yang ada.
     */
    public function edit(AturanFuzzy $aturanFuzzy) // Route Model Binding
    {
        $allParameters = $this->getParameterDataForForm();
        $outputOptions = ['Tidak Layak', 'Layak'];
        
        return view('aturan_fuzzy.edit', [
            'aturan' => $aturanFuzzy,
            'allParameters' => $allParameters,
            'outputOptions' => $outputOptions
        ]);
    }

    /**
     * Memperbarui aturan fuzzy yang ada di database.
     */
    public function update(Request $request, AturanFuzzy $aturanFuzzy)
    {
        // Validasi dasar, nama_aturan sudah dihilangkan
        $request->validate([
            'hasil' => 'required|string',
        ]);

        $allParametersFromDB = Parameter::pluck('nama_parameter')->toArray();
        $kondisiFormatted = [];

        foreach ($allParametersFromDB as $paramName) {
            if ($request->has($paramName) && !empty($request->input($paramName))) {
                $kondisiFormatted[$paramName] = $request->input($paramName);
            }
        }
        
        // Opsional: Memastikan minimal satu kondisi dipilih.
        // if (empty($kondisiFormatted)) {
        //     return back()->with('error', 'Minimal satu kondisi parameter harus dipilih.')->withInput();
        // }

        $aturanFuzzy->update([
            // 'nama_aturan' field dihilangkan
            'kondisi' => $kondisiFormatted,
            'hasil' => $request->hasil,
            // 'aktif' field dihilangkan
        ]);

        return redirect()->route('aturan-fuzzy.index')->with('success', 'Aturan berhasil diperbarui.');
    }

    /**
     * Menghapus aturan fuzzy dari database.
     */
    public function destroy(AturanFuzzy $aturanFuzzy)
    {
        $aturanFuzzy->delete();
        return redirect()->route('aturan-fuzzy.index')->with('success', 'Aturan berhasil dihapus.');
    }
}