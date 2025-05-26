<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Parameter;
use App\Models\AturanFuzzy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahLokasi = Lokasi::count();
        $jumlahParameter = Parameter::count();
        $jumlahAturan = AturanFuzzy::count();
        $lokasiTerbaru = Lokasi::latest()->take(5)->get(); // Ambil 5 lokasi terbaru

        // Anda bisa menambahkan logika untuk menghitung rata-rata skor, dll.
        // $rataRataSkor = Lokasi::avg('skor_lokasi');

        return view('dashboard', compact(
            'jumlahLokasi',
            'jumlahParameter',
            'jumlahAturan',
            'lokasiTerbaru'
            // 'rataRataSkor'
        ));
    }
}