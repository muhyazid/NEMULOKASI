<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ParameterFuzzyController;
use App\Http\Controllers\AturanFuzzyController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Rute Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Rute baru


// Rute Utama & Perhitungan Fuzzy
Route::get('/', [LokasiController::class, 'tampilkanForm'])->name('lokasi.form');
Route::post('/hitung', [LokasiController::class, 'hitungSkorLokasi'])->name('lokasi.hitung');
Route::get('/hasil/{id}', [LokasiController::class, 'tampilkanHasil'])->name('lokasi.hasil');
Route::get('/hasil/{id}/fuzzifikasi', [LokasiController::class, 'tampilkanFuzzifikasi'])->name('lokasi.fuzzifikasi');
Route::get('/hasil/{id}/inferensi', [LokasiController::class, 'tampilkanInferensi'])->name('lokasi.inferensi');
Route::get('/hasil/{id}/nilai-z', [LokasiController::class, 'tampilkanNilaiZ'])->name('lokasi.nilai-z');

// Rute CRUD Parameter (dan Himpunan Fuzzy)
Route::resource('parameters', ParameterFuzzyController::class)->names('parameters');

// Rute CRUD Aturan Fuzzy
Route::resource('aturan-fuzzy', AturanFuzzyController::class)->names('aturan-fuzzy');

// Jika Anda menggunakan Auth, tambahkan middleware
// Route::middleware('auth')->group(function () {
//     Route::resource('parameters', ParameterController::class)->names('parameters');
//     Route::resource('aturan-fuzzy', AturanFuzzyController::class)->names('aturan-fuzzy');
// });

// Anda bisa menambahkan rute lain seperti dashboard di sini
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');