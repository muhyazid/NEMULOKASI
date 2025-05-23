<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;
use App\Models\ParameterFuzzy;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data untuk dashboard
        $totalLokasi = Lokasi::count();
        
        // Lokasi berdasarkan kategori skor
        $lokasiLayak = Lokasi::where('skor_lokasi', '>=', 70)->count();
        $lokasiKurangLayak = Lokasi::where('skor_lokasi', '>=', 50)
                                  ->where('skor_lokasi', '<', 70)
                                  ->count();
        $lokasiTidakLayak = Lokasi::where('skor_lokasi', '<', 50)->count();
        
        // Ambil 5 lokasi terbaru
        $lokasiTerbaru = Lokasi::latest()->take(5)->get();
        
        // Ambil parameter fuzzy
        $parameterFuzzy = ParameterFuzzy::all();
        
        return view('dashboard', compact(
            'totalLokasi', 
            'lokasiLayak', 
            'lokasiKurangLayak', 
            'lokasiTidakLayak', 
            'lokasiTerbaru',
            'parameterFuzzy'
        ));
    }
}