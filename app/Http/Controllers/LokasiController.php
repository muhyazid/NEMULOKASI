<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\ParameterFuzzy;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    public function tampilkanForm()
    {
        return view('form_lokasi');
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
    
        // Langkah 1: Konversi nilai linguistik ke nilai crisp
        // Menggunakan skala nilai yang lebih bervariasi
        $crispAksesibilitas = $this->getNilaiCrisp($dataLokasi['aksesibilitas'], 'aksesibilitas');
        $crispVisibilitas = $this->getNilaiCrisp($dataLokasi['visibilitas'], 'visibilitas');
        $crispDayaBeli = $this->getNilaiCrisp($dataLokasi['daya_beli'], 'daya_beli');
        $crispPersaingan = $this->getNilaiCrisp($dataLokasi['persaingan'], 'persaingan');
        $crispInfrastruktur = $this->getNilaiCrisp($dataLokasi['infrastruktur'], 'infrastruktur');
        $crispLingkunganSekitar = $this->getNilaiCrisp($dataLokasi['lingkungan_sekitar'], 'lingkungan_sekitar');
        $crispParkir = $this->getNilaiCrisp($dataLokasi['parkir'], 'parkir');
        
        // Langkah 2: Hitung derajat keanggotaan fuzzy untuk setiap parameter
        $derajatAksesibilitas = $this->fuzzifikasi($crispAksesibilitas, 'aksesibilitas');
        $derajatVisibilitas = $this->fuzzifikasi($crispVisibilitas, 'visibilitas');
        $derajatDayaBeli = $this->fuzzifikasi($crispDayaBeli, 'daya_beli');
        $derajatPersaingan = $this->fuzzifikasi($crispPersaingan, 'persaingan');
        $derajatInfrastruktur = $this->fuzzifikasi($crispInfrastruktur, 'infrastruktur');
        $derajatLingkunganSekitar = $this->fuzzifikasi($crispLingkunganSekitar, 'lingkungan_sekitar');
        $derajatParkir = $this->fuzzifikasi($crispParkir, 'parkir');
        
        // Langkah 3: Inferensi dengan aturan fuzzy dan menghitung nilai Z
        $hasilInferensi = $this->inferensi([
            'aksesibilitas' => $derajatAksesibilitas,
            'visibilitas' => $derajatVisibilitas,
            'daya_beli' => $derajatDayaBeli,
            'persaingan' => $derajatPersaingan,
            'infrastruktur' => $derajatInfrastruktur,
            'lingkungan_sekitar' => $derajatLingkunganSekitar,
            'parkir' => $derajatParkir
        ]);
        
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
        session(['hasil_perhitungan_' . $lokasi->id => [
            'skor' => $skor,
            'hasil_inferensi' => $hasilInferensi,
            'derajat_aksesibilitas' => $derajatAksesibilitas,
            'derajat_visibilitas' => $derajatVisibilitas,
            'derajat_daya_beli' => $derajatDayaBeli,
            'derajat_persaingan' => $derajatPersaingan,
            'derajat_infrastruktur' => $derajatInfrastruktur,
            'derajat_lingkungan_sekitar' => $derajatLingkunganSekitar,
            'derajat_parkir' => $derajatParkir
        ]]);
    
        // Kirim semua variabel ke view
        return view('hasil_lokasi', compact(
            'skor', 'lokasi', 
            'derajatAksesibilitas', 'derajatVisibilitas', 'derajatDayaBeli', 
            'derajatPersaingan', 'derajatInfrastruktur', 'derajatLingkunganSekitar', 'derajatParkir',
            'hasilInferensi'
        ));
    }

    /**
     * Menampilkan hasil perhitungan
     */
    public function tampilkanHasil($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        
        // Cek apakah ada data perhitungan di session
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            // Jika tidak ada, hitung ulang
            return $this->hitungUlangDanTampilkan($lokasi, 'hasil');
        }
        
        // Ambil data dari session
        $skor = $hasilPerhitungan['skor'];
        $derajatAksesibilitas = $hasilPerhitungan['derajat_aksesibilitas'];
        $derajatVisibilitas = $hasilPerhitungan['derajat_visibilitas'];
        $derajatDayaBeli = $hasilPerhitungan['derajat_daya_beli'];
        $derajatPersaingan = $hasilPerhitungan['derajat_persaingan'];
        $derajatInfrastruktur = $hasilPerhitungan['derajat_infrastruktur'];
        $derajatLingkunganSekitar = $hasilPerhitungan['derajat_lingkungan_sekitar'];
        $derajatParkir = $hasilPerhitungan['derajat_parkir'];
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        
        return view('hasil_lokasi', compact(
            'skor', 'lokasi', 
            'derajatAksesibilitas', 'derajatVisibilitas', 'derajatDayaBeli', 
            'derajatPersaingan', 'derajatInfrastruktur', 'derajatLingkunganSekitar', 'derajatParkir',
            'hasilInferensi'
        ));
    }
    
    /**
     * Menampilkan halaman fuzzifikasi
     */
    public function tampilkanFuzzifikasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            // Jika tidak ada hasil perhitungan di session, hitung ulang
            return $this->hitungUlangDanTampilkan($lokasi, 'fuzzifikasi');
        }
        
        $derajatAksesibilitas = $hasilPerhitungan['derajat_aksesibilitas'];
        $derajatVisibilitas = $hasilPerhitungan['derajat_visibilitas'];
        $derajatDayaBeli = $hasilPerhitungan['derajat_daya_beli'];
        $derajatPersaingan = $hasilPerhitungan['derajat_persaingan'];
        $derajatInfrastruktur = $hasilPerhitungan['derajat_infrastruktur'];
        $derajatLingkunganSekitar = $hasilPerhitungan['derajat_lingkungan_sekitar'];
        $derajatParkir = $hasilPerhitungan['derajat_parkir'];
        
        return view('fuzzifikasi', compact(
            'lokasi',
            'derajatAksesibilitas', 'derajatVisibilitas', 'derajatDayaBeli', 
            'derajatPersaingan', 'derajatInfrastruktur', 'derajatLingkunganSekitar', 'derajatParkir'
        ));
    }
    
    /**
     * Menampilkan halaman inferensi
     */
    public function tampilkanInferensi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            // Jika tidak ada hasil perhitungan di session, hitung ulang
            return $this->hitungUlangDanTampilkan($lokasi, 'inferensi');
        }
        
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        $skor = $hasilPerhitungan['skor'];
        
        return view('inferensi', compact('lokasi', 'hasilInferensi', 'skor'));
    }
    
    /**
     * Menampilkan halaman perhitungan nilai Z
     */
    public function tampilkanNilaiZ($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $hasilPerhitungan = session('hasil_perhitungan_' . $id);
        
        if (!$hasilPerhitungan) {
            // Jika tidak ada hasil perhitungan di session, hitung ulang
            return $this->hitungUlangDanTampilkan($lokasi, 'nilai-z');
        }
        
        $hasilInferensi = $hasilPerhitungan['hasil_inferensi'];
        $skor = $hasilPerhitungan['skor'];
        
        return view('nilai-z', compact('lokasi', 'hasilInferensi', 'skor'));
    }
    
    /**
     * Fungsi bantuan untuk menghitung ulang dan menampilkan halaman yang diminta
     */
    private function hitungUlangDanTampilkan($lokasi, $tampilan)
    {
        // Langkah 1: Konversi nilai linguistik ke nilai crisp
        $crispAksesibilitas = $this->getNilaiCrisp($lokasi->aksesibilitas, 'aksesibilitas');
        $crispVisibilitas = $this->getNilaiCrisp($lokasi->visibilitas, 'visibilitas');
        $crispDayaBeli = $this->getNilaiCrisp($lokasi->daya_beli, 'daya_beli');
        $crispPersaingan = $this->getNilaiCrisp($lokasi->persaingan, 'persaingan');
        $crispInfrastruktur = $this->getNilaiCrisp($lokasi->infrastruktur, 'infrastruktur');
        $crispLingkunganSekitar = $this->getNilaiCrisp($lokasi->lingkungan_sekitar, 'lingkungan_sekitar');
        $crispParkir = $this->getNilaiCrisp($lokasi->parkir, 'parkir');
        
        // Langkah 2: Hitung derajat keanggotaan fuzzy untuk setiap parameter
        $derajatAksesibilitas = $this->fuzzifikasi($crispAksesibilitas, 'aksesibilitas');
        $derajatVisibilitas = $this->fuzzifikasi($crispVisibilitas, 'visibilitas');
        $derajatDayaBeli = $this->fuzzifikasi($crispDayaBeli, 'daya_beli');
        $derajatPersaingan = $this->fuzzifikasi($crispPersaingan, 'persaingan');
        $derajatInfrastruktur = $this->fuzzifikasi($crispInfrastruktur, 'infrastruktur');
        $derajatLingkunganSekitar = $this->fuzzifikasi($crispLingkunganSekitar, 'lingkungan_sekitar');
        $derajatParkir = $this->fuzzifikasi($crispParkir, 'parkir');
        
        // Langkah 3: Inferensi dengan aturan fuzzy dan menghitung nilai Z
        $hasilInferensi = $this->inferensi([
            'aksesibilitas' => $derajatAksesibilitas,
            'visibilitas' => $derajatVisibilitas,
            'daya_beli' => $derajatDayaBeli,
            'persaingan' => $derajatPersaingan,
            'infrastruktur' => $derajatInfrastruktur,
            'lingkungan_sekitar' => $derajatLingkunganSekitar,
            'parkir' => $derajatParkir
        ]);
        
        // Langkah 4: Hitung skor lokasi dengan metode weighted average
        $skor = $lokasi->skor_lokasi ?? $this->hitungWeightedAverage($hasilInferensi);
        
        // Simpan data dalam session agar tersedia untuk tampilan lainnya
        session(['hasil_perhitungan_' . $lokasi->id => [
            'skor' => $skor,
            'hasil_inferensi' => $hasilInferensi,
            'derajat_aksesibilitas' => $derajatAksesibilitas,
            'derajat_visibilitas' => $derajatVisibilitas,
            'derajat_daya_beli' => $derajatDayaBeli,
            'derajat_persaingan' => $derajatPersaingan,
            'derajat_infrastruktur' => $derajatInfrastruktur,
            'derajat_lingkungan_sekitar' => $derajatLingkunganSekitar,
            'derajat_parkir' => $derajatParkir
        ]]);
        
        // Tampilkan halaman yang diminta
        switch ($tampilan) {
            case 'fuzzifikasi':
                return view('fuzzifikasi', compact(
                    'lokasi',
                    'derajatAksesibilitas', 'derajatVisibilitas', 'derajatDayaBeli', 
                    'derajatPersaingan', 'derajatInfrastruktur', 'derajatLingkunganSekitar', 'derajatParkir'
                ));
            case 'inferensi':
                return view('inferensi', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'nilai-z':
                return view('nilai-z', compact('lokasi', 'hasilInferensi', 'skor'));
            case 'hasil':
                return view('hasil_lokasi', compact(
                    'skor', 'lokasi', 
                    'derajatAksesibilitas', 'derajatVisibilitas', 'derajatDayaBeli', 
                    'derajatPersaingan', 'derajatInfrastruktur', 'derajatLingkunganSekitar', 'derajatParkir',
                    'hasilInferensi'
                ));
            default:
                return redirect()->route('lokasi.form');
        }
    }

    // Fungsi untuk mengkonversi nilai linguistik ke nilai crisp
    private function getNilaiCrisp($nilaiLinguistik, $parameter)
    {
        // Tabel konversi untuk nilai crisp (dimodifikasi agar lebih bervariasi)
        // Nilai yang tidak tepat di tengah fungsi keanggotaan
        $konversi = [
            'aksesibilitas' => [
                'sangat mudah' => 8,  // Tinggi
                'sedang' => 5,        // Sedang
                'tidak mudah' => 2    // Rendah
            ],
            'visibilitas' => [
                'sangat terlihat' => 8,    // Tinggi
                'terlihat sebagian' => 5,  // Sedang
                'tidak terlihat' => 2      // Rendah
            ],
            'daya_beli' => [
                'tinggi' => 8,        // Tinggi
                'menengah' => 5,      // Sedang
                'rendah' => 2         // Rendah
            ],
            'persaingan' => [
                'rendah' => 8,        // Tinggi (persaingan rendah = baik)
                'sedang' => 5,        // Sedang
                'tinggi' => 2         // Rendah (persaingan tinggi = buruk)
            ],
            'infrastruktur' => [
                'lengkap' => 8,       // Tinggi
                'cukup' => 5,         // Sedang
                'tidak lengkap' => 2  // Rendah
            ],
            'lingkungan_sekitar' => [
                'sangat mendukung' => 8, // Tinggi
                'netral' => 5,          // Sedang
                'tidak mendukung' => 2   // Rendah
            ],
            'parkir' => [
                'luas' => 8,          // Tinggi
                'sedang' => 5,        // Sedang
                'sempit' => 2         // Rendah
            ]
        ];
        
        // Pastikan case-insensitive
        $nilaiLinguistik = strtolower($nilaiLinguistik);
        
        // Kembalikan nilai crisp sesuai parameter dan nilai linguistik
        if (isset($konversi[$parameter][$nilaiLinguistik])) {
            return $konversi[$parameter][$nilaiLinguistik];
        }
        

        // Log warning jika nilai linguistik tidak ditemukan
        Log::warning("Nilai linguistik tidak ditemukan: $nilaiLinguistik untuk parameter $parameter");

        // Default jika tidak ditemukan
        return 5;
    }
    
    // Fungsi fuzzifikasi: mengkonversi nilai crisp ke derajat keanggotaan fuzzy
    private function fuzzifikasi($nilaiCrisp, $parameter)
    {
        // Nilai batas bawah (a), tengah (b), dan atas (c) untuk kurva segitiga
        // Perbaikan fungsi keanggotaan dengan overlap yang lebih baik
        $batas = [
            'aksesibilitas' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'visibilitas' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'daya_beli' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'persaingan' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'infrastruktur' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'lingkungan_sekitar' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ],
            'parkir' => [
                'rendah' => ['a' => 0, 'b' => 2, 'c' => 5],
                'sedang' => ['a' => 2, 'b' => 5, 'c' => 8],
                'tinggi' => ['a' => 5, 'b' => 8, 'c' => 10]
            ]
        ];
        
        // Menghitung derajat keanggotaan untuk setiap kategori
        $derajatKeanggotaan = [];
        
        foreach (['rendah', 'sedang', 'tinggi'] as $kategori) {
            if (!isset($batas[$parameter][$kategori])) {
                $derajatKeanggotaan[$kategori] = 0;
                continue;
            }
            
            $a = $batas[$parameter][$kategori]['a'];
            $b = $batas[$parameter][$kategori]['b'];
            $c = $batas[$parameter][$kategori]['c'];
            
            // Fungsi keanggotaan kurva segitiga
            if ($nilaiCrisp <= $a || $nilaiCrisp >= $c) {
                $derajatKeanggotaan[$kategori] = 0;
            } elseif ($nilaiCrisp == $b) {
                $derajatKeanggotaan[$kategori] = 1;
            } elseif ($nilaiCrisp > $a && $nilaiCrisp < $b) {
                $derajatKeanggotaan[$kategori] = ($nilaiCrisp - $a) / ($b - $a);
            } else { // $nilaiCrisp > $b && $nilaiCrisp < $c
                $derajatKeanggotaan[$kategori] = ($c - $nilaiCrisp) / ($c - $b);
            }
            
            // Buat hasil lebih akurat dengan pembulatan ke 4 desimal
            $derajatKeanggotaan[$kategori] = round($derajatKeanggotaan[$kategori], 4);
        }
        
        // Temukan kategori dengan derajat tertinggi
        $kategoriTertinggi = 'sedang'; // Default
        $nilaiTertinggi = 0;
        
        foreach ($derajatKeanggotaan as $kategori => $nilai) {
            if ($nilai > $nilaiTertinggi) {
                $nilaiTertinggi = $nilai;
                $kategoriTertinggi = $kategori;
            }
        }
        
        // Hasil lengkap
        $hasil = [
            'derajat' => $nilaiTertinggi,
            'kategori' => $kategoriTertinggi,
            'nilai_crisp' => $nilaiCrisp,
            'semua_derajat' => $derajatKeanggotaan
        ];
        
        return $hasil;
    }
    
    /**
     * Fungsi inferensi: menerapkan aturan fuzzy dan menghitung nilai Z
     * @param array $derajatFuzzy Array berisi hasil fuzzifikasi semua parameter
     * @return array Hasil inferensi dengan alpha dan z untuk setiap aturan
     */
    private function inferensi($derajatFuzzy)
    {
        // Menyiapkan array untuk menyimpan hasil inferensi
        $hasilInferensi = [
            'aturan' => [],
            'total_alpha_z' => 0,
            'total_alpha' => 0
        ];
        
        // Definisikan aturan-aturan fuzzy
        $aturanFuzzy = $this->getAturanFuzzy();
        
        // Loop untuk setiap aturan
        foreach ($aturanFuzzy as $index => $aturan) {
            // Simpan informasi aturan
            $hasilAturan = [
                'nomor_aturan' => $index + 1,
                'kondisi_if' => [],
                'alpha' => 1.0, // nilai alpha diinisialisasi dengan 1.0
                'hasil_then' => $aturan['hasil']
            ];
            
            // Loop untuk setiap parameter dalam kondisi aturan
            foreach ($aturan as $parameter => $kategori) {
                // Skip kunci 'hasil' karena itu bukan parameter
                if ($parameter === 'hasil') {
                    continue;
                }
                
                // Simpan kondisi IF
                $hasilAturan['kondisi_if'][] = [
                    'parameter' => $parameter,
                    'kategori' => $kategori,
                    'derajat' => $derajatFuzzy[$parameter]['semua_derajat'][$kategori] ?? 0
                ];
                
                // Ambil nilai alpha (min dari semua derajat keanggotaan parameter)
                $derajatParameter = $derajatFuzzy[$parameter]['semua_derajat'][$kategori] ?? 0;
                $hasilAturan['alpha'] = min($hasilAturan['alpha'], $derajatParameter);
            }
            
            // Hanya proses aturan dengan alpha > 0
            if ($hasilAturan['alpha'] > 0) {
                // Hitung nilai z menggunakan metode Tsukamoto yang sesuai
                $hasilAturan['z'] = $this->hitungNilaiZ($hasilAturan['alpha'], $hasilAturan['hasil_then']);
                
                // Hitung alpha * z untuk weighted average
                $hasilAturan['alpha_z'] = $hasilAturan['alpha'] * $hasilAturan['z'];
                
                // Tambahkan ke total untuk weighted average
                $hasilInferensi['total_alpha_z'] += $hasilAturan['alpha_z'];
                $hasilInferensi['total_alpha'] += $hasilAturan['alpha'];
                
                // Tambahkan hasil aturan ke array hasil inferensi
                $hasilInferensi['aturan'][] = $hasilAturan;
            }
            
        }
         // Log warning jika tidak ada aturan yang terpicu
         if (count($hasilInferensi['aturan']) == 0) {
            Log::warning("Tidak ada aturan fuzzy yang terpicu dalam inferensi");
        }
        
        return $hasilInferensi;
    }
    
    /**
     * Mendefinisikan aturan-aturan fuzzy untuk inferensi
     * @return array Daftar aturan fuzzy
     */
    private function getAturanFuzzy()
    {
        // Definisikan aturan fuzzy (IF-THEN rules)
        // Kita akan membuat aturan yang lebih lengkap
        return [
            // Aturan 1: Semua parameter tinggi (kondisi ideal)
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah', // persaingan rendah = baik
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'sangat_layak'
            ],
            // Aturan 2: Sebagian besar parameter tinggi, beberapa sedang
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'sedang',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'sedang',
                'hasil' => 'sangat_layak'
            ],
            // Aturan 3: Sebagian parameter tinggi, sebagian sedang
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'sedang',
                'persaingan' => 'sedang',
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'layak'
            ],
            // Aturan 4: Kombinasi tinggi, sedang dan beberapa rendah
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'sedang',
                'daya_beli' => 'sedang',
                'persaingan' => 'tinggi', // persaingan tinggi = tidak baik
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 5: Mayoritas sedang
            [
                'aksesibilitas' => 'sedang',
                'visibilitas' => 'sedang',
                'daya_beli' => 'sedang',
                'persaingan' => 'sedang',
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'sedang',
                'parkir' => 'sedang',
                'hasil' => 'layak'
            ],
            // Aturan 6: Aksesibilitas dan visibilitas tinggi, lainnya sedang
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'sedang',
                'persaingan' => 'sedang',
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'sedang',
                'parkir' => 'sedang',
                'hasil' => 'layak'
            ],
            // Aturan 7: Aksesibilitas rendah, parameter lain bervariasi
            [
                'aksesibilitas' => 'rendah',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 8: Visibilitas rendah, parameter lain bervariasi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'rendah',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 9: Persaingan tinggi, parameter lain bervariasi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'tinggi',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 10: Daya beli rendah, parameter lain tinggi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'rendah',
                'persaingan' => 'rendah',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 11: Lingkungan tidak mendukung, parameter lain bervariasi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'tinggi',
                'hasil' => 'layak'
            ],
            // Aturan 12: Parkir sempit, parameter lain bervariasi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'rendah',
                'hasil' => 'layak'
            ],
            // Aturan 13: Infrastruktur tidak lengkap, parameter lain bervariasi
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'rendah',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'layak'
            ],
            // Aturan 14: Mayoritas parameter rendah
            [
                'aksesibilitas' => 'rendah',
                'visibilitas' => 'rendah',
                'daya_beli' => 'rendah',
                'persaingan' => 'tinggi',
                'infrastruktur' => 'rendah',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'rendah',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 15: Sebagian parameter rendah, sebagian sedang
            [
                'aksesibilitas' => 'rendah',
                'visibilitas' => 'sedang',
                'daya_beli' => 'sedang',
                'persaingan' => 'sedang',
                'infrastruktur' => 'rendah',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'sedang',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 16: Aksesibilitas dan visibilitas tinggi, sisanya rendah
            [
                'aksesibilitas' => 'tinggi',
                'visibilitas' => 'tinggi',
                'daya_beli' => 'rendah',
                'persaingan' => 'tinggi',
                'infrastruktur' => 'rendah',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'rendah',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 17: Daya beli dan persaingan baik, sisanya sedang
            [
                'aksesibilitas' => 'sedang',
                'visibilitas' => 'sedang',
                'daya_beli' => 'tinggi',
                'persaingan' => 'rendah',
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'sedang',
                'parkir' => 'sedang',
                'hasil' => 'layak'
            ],
            // Aturan 18: Lingkungan dan parkir baik, sisanya sedang
            [
                'aksesibilitas' => 'sedang',
                'visibilitas' => 'sedang',
                'daya_beli' => 'sedang',
                'persaingan' => 'sedang',
                'infrastruktur' => 'sedang',
                'lingkungan_sekitar' => 'tinggi',
                'parkir' => 'tinggi',
                'hasil' => 'layak'
            ],
            // Aturan 19: Sebagian besar parameter rendah, beberapa tinggi
            [
                'aksesibilitas' => 'rendah',
                'visibilitas' => 'rendah',
                'daya_beli' => 'tinggi',
                'persaingan' => 'tinggi',
                'infrastruktur' => 'rendah',
                'lingkungan_sekitar' => 'rendah',
                'parkir' => 'tinggi',
                'hasil' => 'tidak_layak'
            ],
            // Aturan 20: Sebagian besar parameter sedang, beberapa tinggi
            [
                'aksesibilitas' => 'sedang',
                'visibilitas' => 'sedang',
                'daya_beli' => 'tinggi',
                'persaingan' => 'sedang',
                'infrastruktur' => 'tinggi',
                'lingkungan_sekitar' => 'sedang',
                'parkir' => 'tinggi',
                'hasil' => 'layak'
            ],
        ];
    }
    
    /**
     * Fungsi untuk menghitung nilai Z dalam metode Tsukamoto
     * @param float $alpha Nilai alpha (derajat keanggotaan)
     * @param string $kategoriHasil Kategori hasil (tidak_layak, layak, sangat_layak)
     * @return float Nilai Z
     */
    private function hitungNilaiZ($alpha, $kategoriHasil)
    {
       // Pastikan kategori hasil valid
       $kategoriValid = ['sangat_layak', 'layak', 'tidak_layak'];
       if (!in_array($kategoriHasil, $kategoriValid)) {
           Log::warning("Kategori hasil tidak valid: $kategoriHasil");
           return 50; // Default jika kategori tidak dikenal
       }
        
        // Pastikan alpha dalam range [0, 1]
        $alpha = max(0, min(1, $alpha));
        
        // Hitung nilai Z berdasarkan fungsi keanggotaan monoton (Tsukamoto)
        switch ($kategoriHasil) {
            case 'tidak_layak':
                // Untuk kategori "tidak layak" (monoton turun): range 0-50
                // Rumus: z = 50 - (50 * alpha)
                $z = 50 - (50 * $alpha);
                break;
                
            case 'layak':
                // Untuk kategori "layak" dalam metode Tsukamoto,
                // kita perlu menggunakan fungsi monoton, bukan segitiga
                // Kita bisa membaginya menjadi 2 bagian seperti berikut:
                
                if ($alpha <= 0.5) {
                    // Fungsi monoton naik dari 40-55
                    $z = 40 + (30 * $alpha);
                } else {
                    // Fungsi monoton turun dari 55-70
                    $z = 70 - (30 * (1 - $alpha));
                }
                break;
                
            case 'sangat_layak':
                // Untuk kategori "sangat layak" (monoton naik): range 60-100
                // Rumus: z = 60 + (40 * alpha)
                $z = 60 + (40 * $alpha);
                break;
                
            default:
                $z = 50; // Default value
                break;
        }
        
        // Pastikan nilai Z dalam range yang valid [0, 100]
        $z = max(0, min(100, $z));
        
        return round($z, 2); // Bulatkan ke 2 desimal
    }
    
    /**
     * Fungsi untuk menghitung weighted average (defuzzifikasi)
     * @param array $hasilInferensi Hasil inferensi
     * @return float Nilai defuzzifikasi (Z)
     */
   // Fungsi hitungWeightedAverage (DIPERBAIKI)
   private function hitungWeightedAverage($hasilInferensi)
   {
       // Validasi input
       if (!isset($hasilInferensi['total_alpha']) || !isset($hasilInferensi['total_alpha_z'])) {
           Log::warning("Struktur data inferensi tidak lengkap untuk weighted average");
           return 50.0; // Nilai default jika struktur data tidak sesuai
       }
       
       // Cek untuk menghindari division by zero
       if ($hasilInferensi['total_alpha'] < 0.0001) { // Gunakan nilai kecil untuk floating point comparison
           Log::warning("Total alpha mendekati nol, tidak ada aturan yang terpicu secara signifikan");
           return 50.0; // Nilai netral/default
       }
       
       // Hitung weighted average menggunakan rumus Tsukamoto:
       // Z = Ʃ(αi * zi) / Ʃαi
       $weightedAverage = $hasilInferensi['total_alpha_z'] / $hasilInferensi['total_alpha'];
       
       // Pastikan dalam range yang valid (0-100)
       $weightedAverage = max(0, min(100, $weightedAverage));
       
       // Bulatkan ke 2 desimal
       return round($weightedAverage, 2);
   }
    
    /**
     * Fungsi untuk menentukan kategori kelayakan berdasarkan skor
     * @param float $skor Skor hasil defuzzifikasi
     * @return string Kategori kelayakan
     */
    private function interpretasiKelayakan($skor)
    {
        if ($skor >= 60) {
            return 'sangat_layak';
        } elseif ($skor >= 40) {
            return 'layak';
        } else {
            return 'tidak_layak';
        }
    }
    
    /**
     * Fungsi untuk menghitung derajat keanggotaan pada output
     * @param float $skor Skor hasil defuzzifikasi
     * @return array Derajat keanggotaan untuk setiap kategori output
     */
    private function hitungDerajatOutput($skor)
    {
        $derajat = [
            'tidak_layak' => 0,
            'layak' => 0,
            'sangat_layak' => 0
        ];
        
        // Hitung derajat keanggotaan untuk "tidak_layak" (monoton turun, range 0-50)
        if ($skor <= 0) {
            $derajat['tidak_layak'] = 1;
        } elseif ($skor < 50) {
            $derajat['tidak_layak'] = (50 - $skor) / 50;
        } else {
            $derajat['tidak_layak'] = 0;
        }
        
        // Hitung derajat keanggotaan untuk "layak" (segitiga, range 40-70)
        if ($skor <= 40 || $skor >= 70) {
            $derajat['layak'] = 0;
        } elseif ($skor > 40 && $skor < 55) {
            $derajat['layak'] = ($skor - 40) / 15;
        } elseif ($skor >= 55 && $skor < 70) {
            $derajat['layak'] = (70 - $skor) / 15;
        }
        
        // Hitung derajat keanggotaan untuk "sangat_layak" (monoton naik, range 60-100)
        if ($skor <= 60) {
            $derajat['sangat_layak'] = 0;
        } elseif ($skor < 100) {
            $derajat['sangat_layak'] = ($skor - 60) / 40;
        } else {
            $derajat['sangat_layak'] = 1;
        }
        
        return $derajat;
    }
}