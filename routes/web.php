<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ParameterFuzzyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AturanFuzzyController;
use App\Http\Controllers\SistemConfigController;

// Dashboard Route  

Route::get('/', [LokasiController::class, 'tampilkanForm'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Lokasi Routes
Route::prefix('lokasi')->name('lokasi.')->group(function () {
    Route::get('/form', [LokasiController::class, 'tampilkanForm'])->name('form');
    Route::post('/hitung', [LokasiController::class, 'hitungSkorLokasi'])->name('hitung');
    Route::get('/{id}/hasil', [LokasiController::class, 'tampilkanHasil'])->name('hasil');
    Route::get('/{id}/fuzzifikasi', [LokasiController::class, 'tampilkanFuzzifikasi'])->name('fuzzifikasi');
    Route::get('/{id}/inferensi', [LokasiController::class, 'tampilkanInferensi'])->name('inferensi');
    Route::get('/{id}/nilai-z', [LokasiController::class, 'tampilkanNilaiZ'])->name('nilai-z');
});

// Routes untuk Parameter Fuzzy
Route::get('/parameter-fuzzy/form', [ParameterFuzzyController::class, 'tampilkanForm'])->name('parameter-fuzzy.form');
Route::post('/parameter-fuzzy/simpan', [ParameterFuzzyController::class, 'simpanParameterFuzzy'])->name('parameter-fuzzy.simpan');


// Parameter Fuzzy Routes
Route::prefix('parameter-fuzzy')->name('parameter-fuzzy.')->group(function () {
    // Form input cepat
    Route::get('/form', [ParameterFuzzyController::class, 'tampilkanForm'])->name('form');
    Route::post('/simpan', [ParameterFuzzyController::class, 'simpanParameterFuzzy'])->name('simpan');
    
    // CRUD lengkap
    Route::get('/', [ParameterFuzzyController::class, 'index'])->name('index');
    Route::get('/create', [ParameterFuzzyController::class, 'create'])->name('create');
    Route::post('/', [ParameterFuzzyController::class, 'store'])->name('store');
    Route::get('/{parameterFuzzy}', [ParameterFuzzyController::class, 'show'])->name('show');
    Route::get('/{parameterFuzzy}/edit', [ParameterFuzzyController::class, 'edit'])->name('edit');
    Route::put('/{parameterFuzzy}', [ParameterFuzzyController::class, 'update'])->name('update');
    Route::delete('/{parameterFuzzy}', [ParameterFuzzyController::class, 'destroy'])->name('destroy');
    
    // Utility routes
    Route::post('/insert-standard', [ParameterFuzzyController::class, 'insertStandardParameters'])->name('insert-standard');
    Route::get('/validate/consistency', [ParameterFuzzyController::class, 'validateParameterConsistency'])->name('validate-consistency');
});

// Aturan Fuzzy Routes
Route::prefix('aturan-fuzzy')->name('aturan-fuzzy.')->group(function () {
    Route::get('/', [AturanFuzzyController::class, 'index'])->name('index');
    Route::get('/create', [AturanFuzzyController::class, 'create'])->name('create');
    Route::post('/', [AturanFuzzyController::class, 'store'])->name('store');
    Route::get('/{aturanFuzzy}', [AturanFuzzyController::class, 'show'])->name('show');
    Route::get('/{aturanFuzzy}/edit', [AturanFuzzyController::class, 'edit'])->name('edit');
    Route::put('/{aturanFuzzy}', [AturanFuzzyController::class, 'update'])->name('update');
    Route::delete('/{aturanFuzzy}', [AturanFuzzyController::class, 'destroy'])->name('destroy');
});

Route::prefix('api')->name('api.')->group(function () {
    // API untuk mendapatkan nilai fuzzy berdasarkan parameter
    Route::get('/parameter-fuzzy/{parameter}/nilai-fuzzy', [ParameterFuzzyController::class, 'getNilaiFuzzyByParameter'])->name('parameter-fuzzy.nilai-fuzzy');
    
    // API untuk validasi parameter consistency
    Route::get('/parameter-fuzzy/validate/consistency', function() {
        $controller = new ParameterFuzzyController();
        $inconsistencies = $controller->validateParameterConsistency();
        
        return response()->json([
            'valid' => empty($inconsistencies),
            'inconsistencies' => $inconsistencies
        ]);
    })->name('parameter-fuzzy.validate');
});