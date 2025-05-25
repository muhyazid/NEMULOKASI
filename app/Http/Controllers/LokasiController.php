<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\ParameterFuzzy;
use App\Models\AturanFuzzy;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    public function tampilkanForm()
    {
        // Ambil parameter options dari database
        $parameterOptions = $this->getParameterOptions();
        
        return view('form_lokasi', compact('parameterOptions'));
    }

    public function hitungSkorLokasi(Request $request)
    {
        // Validasi data yang diterima dari form
        $dataLokasi = $request->validate([
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'aksesibilitas' => 'required|string',
            'visibilitas' => 'required|string',
            'daya_beli' => 'required|string',
            'persaingan' => 'required|string',
            'infrastruktur' => 'required|string',
            'lingkungan_sekitar' => 'required|string',
            'parkir' => 'required|string',
        ]);
        
        // Langkah 1: Konversi nilai linguistik ke nilai crisp dari database
        $crispValues = [];
        $parameters = ['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 
                      'infrastruktur', 'lingkungan_sekitar', 'parkir'];
        
        foreach ($parameters as $param) {
            $crispValues[$param] = $this->getNilaiCrispFromDB($dataLokasi[$param], $param);
        }
        
        // Langkah 2: Hitung derajat keanggotaan fuzzy untuk setiap parameter
        $derajatFuzzy = [];
        foreach ($crispValues as $parameter => $nilaiCrisp) {
            $derajatFuzzy[$parameter] = $this->fuzzifikasi($nilaiCrisp, $parameter);
        }
        
        // Langkah 3: Inferensi dengan aturan fuzzy dari database
        $hasilInferensi = $this->inferensiFromDB($derajatFuzzy);
        
        // Langkah 4: Hitung skor lokasi dengan metode weighted average
        $skor = $this->hitungWeightedAverage($hasilInferensi);
        
        // Simpan lokasi ke database
        $lokasi = Lokasi::create([
            'nama' => $dataLokasi['nama'],
            'alamat' => $dataLokasi['alamat'],
            'aksesibilitas' => $dataLokasi['aksesibilitas'],
            'visibilitas' => $dataLokasi['visibilitas'],
            'daya_beli' => $dataLokasi['daya_beli'],
            'persaingan' => $dataLokasi['persaingan'],
            'infrastruktur' => $dataLokasi['infrastruktur'],
            'lingkungan_sekitar' => $dataLokasi['lingkungan_sekitar'],
            'parkir' => $dataLokasi['parkir'],
            'skor_lokasi' => $skor,
        ]);
        
        // Simpan hasil perhitungan ke dalam session
        $this->simpanHasilKeSession($lokasi->id, $skor, $hasilInferensi, $derajatFuzzy);
        
        // Extract variables untuk view
        $viewData = $this->extractVariablesForView($derajatFuzzy);
        
        return view('hasil_lokasi', array_merge([
            'skor' => $skor, 
            'lokasi' => $lokasi, 
            'hasilInferensi' => $hasilInferensi
        ], $viewData));
    }
    
    // Method untuk mengambil parameter options dari database
    private function getParameterOptions()
    {
        $parameters = ['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 
                      'infrastruktur', 'lingkungan_sekitar', 'parkir'];
        
        $options = [];
        foreach ($parameters as $param) {
            $options[$param] = ParameterFuzzy::where('nama_parameter', $param)
                                           ->orderBy('nilai_crisp')
                                           ->pluck('nilai_fuzzy', 'nilai_fuzzy')
                                           ->toArray();
        }
        
        return $options;
    }
    
    // Method untuk mengambil nilai crisp dari database
    private function getNilaiCrispFromDB($nilaiLinguistik, $parameter)
    {
        $parameterFuzzy = ParameterFuzzy::where('nama_parameter', $parameter)
                                       ->where('nilai_fuzzy', strtolower($nilaiLinguistik))
                                       ->first();
        
        if ($parameterFuzzy) {
            return $parameterFuzzy->nilai_crisp;
        }
        
        Log::warning("Nilai linguistik tidak ditemukan: $nilaiLinguistik untuk parameter $parameter");
        
        // Ambil nilai default (nilai tengah) untuk parameter tersebut
        $defaultValue = ParameterFuzzy::where('nama_parameter', $parameter)
                                     ->orderBy('nilai_crisp')
                                     ->skip(1)
                                     ->first();
        
        return $defaultValue ? $defaultValue->nilai_crisp : 5; // Default fallback
    }
    
    // Method fuzzifikasi dengan batas hardcode tapi bisa dikonfigurasi nanti
    private function fuzzifikasi($nilaiCrisp, $parameter)
    {
        // Batas fuzzy hardcode (bisa dipindah ke config atau database nanti)
        $batas = [
            'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],   // Puncak di 2
            'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],   // Puncak di 5  
            'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]   // Puncak di 8
        ];
        
        $derajatKeanggotaan = [];
        
        foreach (['rendah', 'sedang', 'tinggi'] as $kategori) {
            $a = $batas[$kategori]['a'];
            $b = $batas[$kategori]['b'];
            $c = $batas[$kategori]['c'];
            
            // Fungsi keanggotaan kurva segitiga
            if ($nilaiCrisp <= $a || $nilaiCrisp >= $c) {
                $derajatKeanggotaan[$kategori] = 0;
            } elseif ($nilaiCrisp == $b) {
                $derajatKeanggotaan[$kategori] = 1;
            } elseif ($nilaiCrisp > $a && $nilaiCrisp < $b) {
                // Sisi naik
                $derajatKeanggotaan[$kategori] = ($nilaiCrisp - $a) / ($b - $a);
            } else { // $nilaiCrisp > $b && $nilaiCrisp < $c
                // Sisi turun
                $derajatKeanggotaan[$kategori] = ($c - $nilaiCrisp) / ($c - $b);
            }
            
            // Bulatkan ke 4 desimal
            $derajatKeanggotaan[$kategori] = round($derajatKeanggotaan[$kategori], 4);
        }
        
        // Temukan kategori dengan derajat tertinggi
        $kategoriTertinggi = 'sedang';
        $nilaiTertinggi = 0;
        
        foreach ($derajatKeanggotaan as $kategori => $nilai) {
            if ($nilai > $nilaiTertinggi) {
                $nilaiTertinggi = $nilai;
                $kategoriTertinggi = $kategori;
            }
        }
        
        return [
            'derajat' => $nilaiTertinggi,
            'kategori' => $kategoriTertinggi,
            'nilai_crisp' => $nilaiCrisp,
            'semua_derajat' => $derajatKeanggotaan
        ];
    }
    
    // Method inferensi menggunakan aturan dari database
    private function inferensiFromDB($derajatFuzzy)
    {
        $hasilInferensi = [
            'aturan' => [],
            'total_alpha_z' => 0,
            'total_alpha' => 0
        ];
        
        // Ambil semua aturan yang aktif dari database
        $aturanFuzzy = AturanFuzzy::aktif()->get();
        
        if ($aturanFuzzy->isEmpty()) {
            Log::warning("Tidak ada aturan fuzzy yang aktif ditemukan");
            return $hasilInferensi;
        }
        
        foreach ($aturanFuzzy as $index => $aturan) {
            $hasilAturan = [
                'nomor_aturan' => $index + 1,
                'nama_aturan' => $aturan->nama_aturan,
                'kondisi_if' => [],
                'alpha' => 1.0,
                'hasil_then' => $aturan->hasil
            ];
            
            // Loop untuk setiap parameter dalam kondisi aturan
            foreach ($aturan->kondisi as $parameter => $kategori) {
                if (!isset($derajatFuzzy[$parameter])) {
                    Log::warning("Parameter $parameter tidak ditemukan dalam hasil fuzzifikasi");
                    continue;
                }
                
                $derajatParameter = $derajatFuzzy[$parameter]['semua_derajat'][$kategori] ?? 0;
                
                $hasilAturan['kondisi_if'][] = [
                    'parameter' => $parameter,
                    'kategori' => $kategori,
                    'derajat' => $derajatParameter
                ];
                
                $hasilAturan['alpha'] = min($hasilAturan['alpha'], $derajatParameter);
            }
            
            // Hanya proses aturan dengan alpha > 0
            if ($hasilAturan['alpha'] > 0) {
                $hasilAturan['z'] = $this->hitungNilaiZ($hasilAturan['alpha'], $hasilAturan['hasil_then']);
                $hasilAturan['alpha_z'] = $hasilAturan['alpha'] * $hasilAturan['z'];
                
                $hasilInferensi['total_alpha_z'] += $hasilAturan['alpha_z'];
                $hasilInferensi['total_alpha'] += $hasilAturan['alpha'];
                $hasilInferensi['aturan'][] = $hasilAturan;
            }
        }
        
        if (count($hasilInferensi['aturan']) == 0) {
            Log::warning("Tidak ada aturan fuzzy yang terpicu dalam inferensi");
        }
        
        return $hasilInferensi;
    }
    
    private function hitungNilaiZ($alpha, $kategoriHasil)
    {
        $kategoriValid = ['sangat_layak', 'layak', 'tidak_layak'];
        if (!in_array($kategoriHasil, $kategoriValid)) {
            Log::warning("Kategori hasil tidak valid: $kategoriHasil");
            return 50;
        }
        
        $alpha = max(0, min(1, $alpha));
        
        switch ($kategoriHasil) {
            case 'tidak_layak':
                $z = 50 - (50 * $alpha);
                break;
            case 'layak':
                if ($alpha <= 0.5) {
                    $z = 40 + (30 * $alpha);
                } else {
                    $z = 70 - (30 * (1 - $alpha));
                }
                break;
            case 'sangat_layak':
                $z = 60 + (40 * $alpha);
                break;
            default:
                $z = 50;
                break;
        }
        
        $z = max(0, min(100, $z));
        return round($z, 2);
    }
    
    private function hitungWeightedAverage($hasilInferensi)
    {
        if (!isset($hasilInferensi['total_alpha']) || !isset($hasilInferensi['total_alpha_z'])) {
            Log::warning("Struktur data inferensi tidak lengkap untuk weighted average");
            return 50.0;
        }
        
        if ($hasilInferensi['total_alpha'] < 0.0001) {
            Log::warning("Total alpha mendekati nol, tidak ada aturan yang terpicu secara signifikan");
            return 50.0;
        }
        
        $weightedAverage = $hasilInferensi['total_alpha_z'] / $hasilInferensi['total_alpha'];
        $weightedAverage = max(0, min(100, $weightedAverage));
        
        return round($weightedAverage, 2);
    }
    
    // Method untuk menyimpan hasil ke session
    private function simpanHasilKeSession($lokasiId, $skor, $hasilInferensi, $derajatFuzzy)
    {
        session(['hasil_perhitungan_' . $lokasiId => [
            'skor' => $skor,
            'hasil_inferensi' => $hasilInferensi,
            'derajat_aksesibilitas' => $derajatFuzzy['aksesibilitas'] ?? null,
            'derajat_visibilitas' => $derajatFuzzy['visibilitas'] ?? null,
            'derajat_daya_beli' => $derajatFuzzy['daya_beli'] ?? null,
            'derajat_persaingan' => $derajatFuzzy['persaingan'] ?? null,
            'derajat_infrastruktur' => $derajatFuzzy['infrastruktur'] ?? null,
            'derajat_lingkungan_sekitar' => $derajatFuzzy['lingkungan_sekitar'] ?? null,
            'derajat_parkir' => $derajatFuzzy['parkir'] ?? null
        ]]);
    }
    
    // Method untuk extract variables untuk view
    private function extractVariablesForView($derajatFuzzy)
    {
        return [
            'derajatAksesibilitas' => $derajatFuzzy['aksesibilitas'] ?? null,
            'derajatVisibilitas' => $derajatFuzzy['visibilitas'] ?? null,
            'derajatDayaBeli' => $derajatFuzzy['daya_beli'] ?? null,
            'derajatPersaingan' => $derajatFuzzy['persaingan'] ?? null,
            'derajatInfrastruktur' => $derajatFuzzy['infrastruktur'] ?? null,
            'derajatLingkunganSekitar' => $derajatFuzzy['lingkungan_sekitar'] ?? null,
            'derajatParkir' => $derajatFuzzy['parkir'] ?? null
        ];
    }
    
    public function tampilkanHasil($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            return $this->hitungUlangDanTampilkan($lokasi, 'hasil');
        }
        
        $skor = $hasilPerhitungan['skor'];
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        $viewData = $this->extractVariablesFromSession($hasilPerhitungan);
        
        return view('hasil_lokasi', array_merge([
            'skor' => $skor, 
            'lokasi' => $lokasi, 
            'hasilInferensi' => $hasilInferensi
        ], $viewData));
    }
    
    public function tampilkanFuzzifikasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            return $this->hitungUlangDanTampilkan($lokasi, 'fuzzifikasi');
        }
        
        $viewData = $this->extractVariablesFromSession($hasilPerhitungan);
        
        return view('fuzzifikasi', array_merge(['lokasi' => $lokasi], $viewData));
    }

    public function tampilkanInferensi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            return $this->hitungUlangDanTampilkan($lokasi, 'inferensi');
        }
        
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        $skor = $hasilPerhitungan['skor'];
        
        return view('inferensi', compact('lokasi', 'hasilInferensi', 'skor'));
    }

    public function tampilkanNilaiZ($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            return $this->hitungUlangDanTampilkan($lokasi, 'nilai-z');
        }
        
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        $skor = $hasilPerhitungan['skor'];
        
        return view('nilai-z', compact('lokasi', 'hasilInferensi', 'skor'));
    }
    
    private function extractVariablesFromSession($hasilPerhitungan)
    {
        return [
            'derajatAksesibilitas' => $hasilPerhitungan['derajat_aksesibilitas'],
            'derajatVisibilitas' => $hasilPerhitungan['derajat_visibilitas'],
            'derajatDayaBeli' => $hasilPerhitungan['derajat_daya_beli'],
            'derajatPersaingan' => $hasilPerhitungan['derajat_persaingan'],
            'derajatInfrastruktur' => $hasilPerhitungan['derajat_infrastruktur'],
            'derajatLingkunganSekitar' => $hasilPerhitungan['derajat_lingkungan_sekitar'],
            'derajatParkir' => $hasilPerhitungan['derajat_parkir']
        ];
    }
    
    private function hitungUlangDanTampilkan($lokasi, $tampilan)
    {
        // Ambil data linguistik dari lokasi yang tersimpan
        $dataLokasi = [
            'aksesibilitas' => $lokasi->aksesibilitas,
            'visibilitas' => $lokasi->visibilitas,
            'daya_beli' => $lokasi->daya_beli,
            'persaingan' => $lokasi->persaingan,
            'infrastruktur' => $lokasi->infrastruktur,
            'lingkungan_sekitar' => $lokasi->lingkungan_sekitar,
            'parkir' => $lokasi->parkir
        ];
        
        // Hitung ulang semua nilai
        $crispValues = [];
        foreach ($dataLokasi as $param => $nilaiLinguistik) {
            $crispValues[$param] = $this->getNilaiCrispFromDB($nilaiLinguistik, $param);
        }
        
        $derajatFuzzy = [];
        foreach ($crispValues as $parameter => $nilaiCrisp) {
            $derajatFuzzy[$parameter] = $this->fuzzifikasi($nilaiCrisp, $parameter);
        }
        
        $hasilInferensi = $this->inferensiFromDB($derajatFuzzy);
        $skor = $lokasi->skor_lokasi ?? $this->hitungWeightedAverage($hasilInferensi);
        
        // Simpan ke session
        $this->simpanHasilKeSession($lokasi->id, $skor, $hasilInferensi, $derajatFuzzy);
        
        // Extract variables untuk view
        $viewData = $this->extractVariablesForView($derajatFuzzy);
        
        switch ($tampilan) {
            case 'fuzzifikasi':
                return view('fuzzifikasi', array_merge(['lokasi' => $lokasi], $viewData));
            case 'inferensi':
                return view('inferensi', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'nilai-z':
                return view('nilai-z', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'hasil':
                return view('hasil_lokasi', array_merge([
                    'skor' => $skor, 
                    'lokasi' => $lokasi, 
                    'hasilInferensi' => $hasilInferensi
                ], $viewData));
            default:
                return redirect()->route('lokasi.form');
        }
    }
}