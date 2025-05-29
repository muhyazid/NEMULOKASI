<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ParameterFuzzyController;
use App\Http\Controllers\AturanFuzzyController;
use App\Http\Controllers\TempatBisnisController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes (disediakan oleh Breeze)
require __DIR__.'/auth.php';

// Protected routes (memerlukan login)
Route::middleware('auth')->group(function () {
    
    // Dashboard - bisa diakses admin dan user
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Analisis Lokasi - bisa diakses admin dan user
    Route::prefix('lokasi')->group(function () {
        Route::get('/form', [LokasiController::class, 'tampilkanForm'])->name('lokasi.form');
        Route::post('/hitung', [LokasiController::class, 'hitungSkorLokasi'])->name('lokasi.hitung');
        Route::get('/hasil/{id}', [LokasiController::class, 'tampilkanHasil'])->name('lokasi.hasil');
        Route::get('/hasil/{id}/fuzzifikasi', [LokasiController::class, 'tampilkanFuzzifikasi'])->name('lokasi.fuzzifikasi');
        Route::get('/hasil/{id}/inferensi', [LokasiController::class, 'tampilkanInferensi'])->name('lokasi.inferensi');
        Route::get('/hasil/{id}/nilai-z', [LokasiController::class, 'tampilkanNilaiZ'])->name('lokasi.nilai-z');
    });
    
    // API untuk tempat bisnis - bisa diakses admin dan user
    Route::get('/api/tempat-bisnis/{id}', [LokasiController::class, 'getTempatBisnisDetail'])->name('api.tempat_bisnis.detail');
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        // CRUD Parameter
        Route::resource('parameters', ParameterFuzzyController::class)->names('parameters');
        
        // CRUD Aturan Fuzzy
        Route::resource('aturan-fuzzy', AturanFuzzyController::class)->names('aturan-fuzzy');
        
        // CRUD Tempat Bisnis
        Route::resource('tempat-bisnis', TempatBisnisController::class)
            ->parameters(['tempat-bisnis' => 'tempatBisni']);
    });
    
    // Profile routes (disediakan oleh Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});