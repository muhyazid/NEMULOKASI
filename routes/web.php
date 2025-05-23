<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ParameterFuzzyController;
use App\Http\Controllers\DashboardController;

// Dashboard Route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routes untuk Lokasi
Route::get('/lokasi/form', [LokasiController::class, 'tampilkanForm'])->name('lokasi.form');
Route::post('/lokasi/hitung', [LokasiController::class, 'hitungSkorLokasi'])->name('lokasi.hitung');

// Routes baru untuk proses fuzzy step by step
Route::get('/lokasi/{id}/hasil', [LokasiController::class, 'tampilkanHasil'])->name('lokasi.hasil');
Route::get('/lokasi/{id}/fuzzifikasi', [LokasiController::class, 'tampilkanFuzzifikasi'])->name('lokasi.fuzzifikasi');
Route::get('/lokasi/{id}/inferensi', [LokasiController::class, 'tampilkanInferensi'])->name('lokasi.inferensi');
Route::get('/lokasi/{id}/nilai-z', [LokasiController::class, 'tampilkanNilaiZ'])->name('lokasi.nilai-z');

// Routes untuk Parameter Fuzzy
Route::get('/parameter-fuzzy/form', [ParameterFuzzyController::class, 'tampilkanForm'])->name('parameter-fuzzy.form');
Route::post('/parameter-fuzzy/simpan', [ParameterFuzzyController::class, 'simpanParameterFuzzy'])->name('parameter-fuzzy.simpan');