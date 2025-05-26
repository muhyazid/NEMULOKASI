<?php

namespace App\Http\Controllers;
use App\Models\Lokasi;
use App\Models\Parameter;
use App\Models\HimpunanFuzzy;
use App\Models\AturanFuzzy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class LokasiController extends Controller
{
    private $threshold = 60; // Tentukan threshold kelayakan di sini

    // Method untuk menampilkan form input lokasi
    public function tampilkanForm()
    {
        $parameterOptions = $this->getParameterOptions();
        return view('form_lokasi', compact('parameterOptions'));
    }

    // Method utama untuk menghitung skor lokasi
    public function hitungSkorLokasi(Request $request)
    {
        $parameterNames = $this->getParameterNames();
        $rules = [];
        foreach($parameterNames as $pName) {
            $rules[$pName] = 'required|string';
        }
        $rules['nama'] = 'required|string|max:255';
        $rules['alamat'] = 'nullable|string';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('lokasi.form')
                        ->withErrors($validator)
                        ->withInput();
        }

        $dataLokasi = $validator->validated();

        // Langkah 1: Konversi nilai linguistik ke nilai crisp DARI DATABASE
        $crispValues = [];
        foreach ($parameterNames as $param) {
            $crispValues[$param] = $this->getNilaiCrispFromDB($dataLokasi[$param], $param);
        }

        // Langkah 2: Fuzzifikasi (Menghitung derajat keanggotaan)
        $derajatFuzzy = [];
        foreach ($crispValues as $parameter => $nilaiCrisp) {
            $derajatFuzzy[$parameter] = $this->fuzzifikasi($nilaiCrisp, $parameter);
        }

        // Langkah 3: Inferensi dengan aturan fuzzy DARI DATABASE
        $hasilInferensi = $this->inferensiFromDB($derajatFuzzy);

        // Langkah 4: Defuzzifikasi (Metode Tsukamoto - Weighted Average)
        $skor = $this->hitungWeightedAverage($hasilInferensi);

        // Tentukan kelayakan berdasarkan threshold
        $kelayakan = $skor >= $this->threshold ? 'Layak' : 'Kurang Layak';

        // Simpan lokasi ke database
        $lokasiDataToSave = $dataLokasi;
        $lokasiDataToSave['skor_lokasi'] = $skor;
        $lokasiDataToSave['kelayakan'] = $kelayakan;

        $lokasi = Lokasi::create($lokasiDataToSave);

        // Simpan hasil perhitungan ke dalam session
        $this->simpanHasilKeSession($lokasi->id, $skor, $kelayakan, $hasilInferensi, $derajatFuzzy, $crispValues);

        // Extract variables untuk view
        $viewData = $this->extractVariablesForView($derajatFuzzy, $crispValues);

        return view('hasil_lokasi', array_merge([
            'skor' => $skor,
            'kelayakan' => $kelayakan,
            'lokasi' => $lokasi,
            'hasilInferensi' => $hasilInferensi,
            'threshold' => $this->threshold,
        ], $viewData));
    }

    // Mengambil nama-nama parameter
    private function getParameterNames()
    {
        return Parameter::pluck('nama_parameter')->toArray();
    }

    // Mengambil opsi parameter (untuk dropdown) dari database
    private function getParameterOptions()
    {
        $parameters = Parameter::with('himpunanFuzzies')->get();
        $options = [];

        foreach ($parameters as $parameter) {
            $options[$parameter->nama_parameter] = $parameter->himpunanFuzzies
                ->pluck('nilai_linguistik_view', 'nilai_linguistik_view') // Pakai nilai_linguistik_view untuk key dan value
                ->toArray();
        }
        return $options;
    }

    // Mengambil nilai crisp input (2.5, 4.5, 7.5, etc.) dari database
    private function getNilaiCrispFromDB($nilaiLinguistikView, $parameterName)
    {
        $parameter = Parameter::where('nama_parameter', $parameterName)->first();
        if (!$parameter) {
            Log::warning("Parameter tidak ditemukan: $parameterName");
            return 5; // Default fallback
        }

        $himpunan = HimpunanFuzzy::where('parameter_id', $parameter->id)
            ->where('nilai_linguistik_view', $nilaiLinguistikView)
            ->first();

        if ($himpunan) {
            return $himpunan->nilai_crisp_input;
        }

        Log::warning("Nilai linguistik tidak ditemukan: $nilaiLinguistikView untuk parameter $parameterName");
        return 5; // Default fallback
    }

    // Fungsi Keanggotaan Segitiga
    private function hitungSegitiga($x, $a, $b, $c)
    {
        if ($b < $a || $c < $b) {
             Log::error("Batas fungsi keanggotaan tidak valid: a=$a, b=$b, c=$c");
             return 0;
        }
        if ($x <= $a || $x >= $c) {
            return 0;
        } elseif ($x == $b) {
            return 1;
        } elseif ($x > $a && $x < $b) {
            return ($b - $a == 0) ? 1 : ($x - $a) / ($b - $a);
        } else { // $x > $b && $x < $c
            return ($c - $b == 0) ? 1 : ($c - $x) / ($c - $b);
        }
    }

    // Method fuzzifikasi BARU: Menghitung derajat untuk SEMUA himpunan
    private function fuzzifikasi($nilaiCrisp, $parameterName)
    {
        $parameter = Parameter::where('nama_parameter', $parameterName)->first();
        if (!$parameter) {
            Log::error("Parameter tidak ditemukan saat fuzzifikasi: $parameterName");
            return [];
        }

        $himpunanFuzzies = HimpunanFuzzy::where('parameter_id', $parameter->id)->get();
        $derajatKeanggotaan = [];

        foreach ($himpunanFuzzies as $himpunan) {
            $nilai = $this->hitungSegitiga($nilaiCrisp, $himpunan->mf_a, $himpunan->mf_b, $himpunan->mf_c);
            $derajatKeanggotaan[$himpunan->nama_himpunan] = round($nilai, 4);
        }

        return $derajatKeanggotaan; // Mengembalikan array [nama_himpunan => derajat]
    }

    // Method inferensi menggunakan aturan dari database BARU
   private function inferensiFromDB($derajatFuzzy)
    {
        $hasilInferensi = [ 'aturan' => [], 'total_alpha_z' => 0, 'total_alpha' => 0 ];
        // $aturanFuzzy = AturanFuzzy::aktif()->get(); // Baris lama
        $aturanFuzzy = AturanFuzzy::all(); // BARIS BARU: Ambil semua aturan

        if ($aturanFuzzy->isEmpty()) {
            Log::warning("Tidak ada aturan fuzzy yang ditemukan di database."); // Pesan diubah
            return $hasilInferensi;
        }

        foreach ($aturanFuzzy as $index => $aturan) {
            $alpha = 1.0;
            $kondisiIf = [];
            $semuaKondisiTerpenuhi = true;

            // Jika $aturan->kondisi null atau bukan array, skip aturan ini
            if (empty($aturan->kondisi) || !is_array($aturan->kondisi)) {
                Log::warning("Aturan ID {$aturan->id} tidak memiliki kondisi yang valid atau kosong.");
                continue; 
            }

            foreach ($aturan->kondisi as $param => $himpunan) {
                if (!isset($derajatFuzzy[$param][$himpunan])) {
                    Log::warning("Parameter '$param' atau himpunan '$himpunan' tidak ditemukan dalam hasil fuzzifikasi untuk aturan ID {$aturan->id}");
                    $semuaKondisiTerpenuhi = false;
                    $derajatParameter = 0;
                } else {
                    $derajatParameter = $derajatFuzzy[$param][$himpunan];
                }

                $kondisiIf[] = [ 'parameter' => $param, 'himpunan' => $himpunan, 'derajat' => $derajatParameter ];
                $alpha = min($alpha, $derajatParameter);
            }

            if ($alpha > 0 && $semuaKondisiTerpenuhi) {
                $z = $this->hitungNilaiZ($alpha, $aturan->hasil);
                $alpha_z = $alpha * $z;

                $hasilInferensi['total_alpha_z'] += $alpha_z;
                $hasilInferensi['total_alpha'] += $alpha;

                $hasilInferensi['aturan'][] = [
                    'nomor_aturan' => $aturan->id, // Gunakan ID aturan sebagai nomor
                    'nama_aturan' => 'R' . $aturan->id, // Tampilkan R + ID jika diperlukan untuk display
                    'kondisi_if' => $kondisiIf, 
                    'alpha' => round($alpha, 4),
                    'hasil_then' => $aturan->hasil, 
                    'z' => round($z, 2),
                    'alpha_z' => round($alpha_z, 4),
                ];
            }
        }
        if (empty($hasilInferensi['aturan'])) { // Periksa apakah array aturan kosong
            Log::warning("Tidak ada aturan fuzzy yang terpicu dalam inferensi setelah evaluasi.");
        }
        return $hasilInferensi;
    }

    // Menghitung Nilai Z (Tsukamoto)
    private function hitungNilaiZ($alpha, $kategoriHasil)
    {
        // Batas output (Bisa dari DB/Config)
        $batasOutput = [
            'tidak_layak' => ['min' => 0, 'max' => 60],
            'layak' => ['min' => 60, 'max' => 100],
        ];

        // Definisikan 'layak' dan 'tidak_layak' saja
        $kategoriValid = ['layak', 'tidak_layak'];
        if (!in_array($kategoriHasil, $kategoriValid)) {
            Log::warning("Kategori hasil tidak valid: $kategoriHasil. Menggunakan 'tidak_layak'");
            $kategoriHasil = 'tidak_layak';
        }

        $alpha = max(0, min(1, $alpha));

        // Menggunakan fungsi linier sederhana
        // Jika tidak layak, Z bergerak dari max ke min (turun)
        // Jika layak, Z bergerak dari min ke max (naik)
        $min = $batasOutput[$kategoriHasil]['min'];
        $max = $batasOutput[$kategoriHasil]['max'];

        if ($kategoriHasil === 'tidak_layak') {
             // Jika alpha = 1 (sangat tidak layak), maka Z = min
             // Jika alpha = 0 (mendekati layak), maka Z = max
             $z = $max - ($max - $min) * $alpha;
        } else { // 'layak'
             // Jika alpha = 1 (sangat layak), maka Z = max
             // Jika alpha = 0 (mendekati tidak layak), maka Z = min
             $z = $min + ($max - $min) * $alpha;
        }

        return max(0, min(100, $z));
    }


    // Menghitung Weighted Average (Defuzzifikasi)
    private function hitungWeightedAverage($hasilInferensi)
    {
        if ($hasilInferensi['total_alpha'] < 0.0001) return 0.0;
        return round($hasilInferensi['total_alpha_z'] / $hasilInferensi['total_alpha'], 2);
    }

    // Menyimpan hasil ke session
    private function simpanHasilKeSession($lokasiId, $skor, $kelayakan, $hasilInferensi, $derajatFuzzy, $crispValues)
    {
        $dataToSave = [ 'skor' => $skor, 'kelayakan' => $kelayakan, 'hasil_inferensi' => $hasilInferensi ];
        foreach ($derajatFuzzy as $key => $value) {
            $dataToSave['derajat_' . $key] = $value;
            $dataToSave['crisp_' . $key] = $crispValues[$key];
        }
        session(['hasil_perhitungan_' . $lokasiId => $dataToSave]);
    }

    // Mengambil data untuk view
    private function extractVariablesForView($derajatFuzzy, $crispValues)
    {
        $data = [];
        foreach ($derajatFuzzy as $key => $value) {
            $data['derajat' . ucfirst($key)] = $value;
            $data['crisp' . ucfirst($key)] = $crispValues[$key];
        }
        return $data;
    }

    // Mengambil data dari session untuk view
    private function extractVariablesFromSession($hasilPerhitungan)
    {
        $data = [];
        $parameters = $this->getParameterNames();
        foreach ($parameters as $param) {
            $data['derajat' . ucfirst($param)] = $hasilPerhitungan['derajat_' . $param] ?? [];
            $data['crisp' . ucfirst($param)] = $hasilPerhitungan['crisp_' . $param] ?? null;
        }
        return $data;
    }

    // Menampilkan hasil
    public function tampilkanHasil($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);

        if (!$hasilPerhitungan) return $this->hitungUlangDanTampilkan($lokasi, 'hasil');

        return view('hasil_lokasi', [
            'skor' => $hasilPerhitungan['skor'],
            'kelayakan' => $hasilPerhitungan['kelayakan'],
            'lokasi' => $lokasi,
            'hasilInferensi' => $hasilPerhitungan['hasil_inferensi'],
            'threshold' => $this->threshold,
        ] + $this->extractVariablesFromSession($hasilPerhitungan));
    }

    // Menampilkan Fuzzifikasi
    public function tampilkanFuzzifikasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);

        if (!$hasilPerhitungan) return $this->hitungUlangDanTampilkan($lokasi, 'fuzzifikasi');

        return view('fuzzifikasi', [
            'lokasi' => $lokasi
            ] + $this->extractVariablesFromSession($hasilPerhitungan));
    }

    // Menampilkan Inferensi
    public function tampilkanInferensi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);

        if (!$hasilPerhitungan) return $this->hitungUlangDanTampilkan($lokasi, 'inferensi');

        return view('inferensi', [
            'lokasi' => $lokasi,
            'hasilInferensi' => $hasilPerhitungan['hasil_inferensi'],
            'skor' => $hasilPerhitungan['skor'],
        ]);
    }

    // Menampilkan Nilai Z
     public function tampilkanNilaiZ($id)
     {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);

        if (!$hasilPerhitungan) return $this->hitungUlangDanTampilkan($lokasi, 'nilai-z');

        return view('nilai-z', [
            'lokasi' => $lokasi,
            'hasilInferensi' => $hasilPerhitungan['hasil_inferensi'],
            'skor' => $hasilPerhitungan['skor'],
        ]);
     }

    // Menghitung ulang jika session hilang
    private function hitungUlangDanTampilkan($lokasi, $tampilan)
    {
        $dataLokasi = $lokasi->toArray();
        $parameters = $this->getParameterNames();

        $crispValues = [];
        foreach ($parameters as $param) {
            $crispValues[$param] = $this->getNilaiCrispFromDB($dataLokasi[$param], $param);
        }

        $derajatFuzzy = [];
        foreach ($crispValues as $parameter => $nilaiCrisp) {
            $derajatFuzzy[$parameter] = $this->fuzzifikasi($nilaiCrisp, $parameter);
        }

        $hasilInferensi = $this->inferensiFromDB($derajatFuzzy);
        $skor = $lokasi->skor_lokasi ?? $this->hitungWeightedAverage($hasilInferensi);
        $kelayakan = $lokasi->kelayakan ?? ($skor >= $this->threshold ? 'Layak' : 'Kurang Layak');

        $this->simpanHasilKeSession($lokasi->id, $skor, $kelayakan, $hasilInferensi, $derajatFuzzy, $crispValues);

        $viewData = $this->extractVariablesForView($derajatFuzzy, $crispValues);

        switch ($tampilan) {
            case 'fuzzifikasi': return view('fuzzifikasi', ['lokasi' => $lokasi] + $viewData);
            case 'inferensi': return view('inferensi', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'nilai-z': return view('nilai-z', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'hasil':
            default:
                return view('hasil_lokasi', [
                    'skor' => $skor, 'kelayakan' => $kelayakan, 'lokasi' => $lokasi,
                    'hasilInferensi' => $hasilInferensi, 'threshold' => $this->threshold
                ] + $viewData);
        }
    }
}