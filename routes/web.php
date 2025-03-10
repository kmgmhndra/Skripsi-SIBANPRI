<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\KelompokTaniController;
use App\Http\Controllers\SeleksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route ke halaman awal
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route dashboard dengan middleware auth dan verified
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Kelompok route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Route untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk Kriteria
    Route::resource('kriteria', KriteriaController::class)->except(['show']); // Menghindari duplikasi
    Route::post('/kriteria/update-urutan', [KriteriaController::class, 'updateUrutan'])->name('kriteria.updateUrutan');
    
    // Route untuk Kelompok Tani
    Route::get('/kelompok-tani', [KelompokTaniController::class, 'index'])
        ->name('kelompok-tani.index');
    Route::get('/kelompok-tani/filter/{kecamatan}', [KelompokTaniController::class, 'filterByKecamatan'])
        ->name('kelompok-tani.filter');
    // Route untuk import Kelompok Tani
    Route::post('/kelompok-tani/import', [KelompokTaniController::class, 'import'])
        ->name('kelompok-tani.import');
    // Resource route kecuali index
    Route::resource('kelompok-tani', KelompokTaniController::class)->except(['index']);

    // Route untuk Hasil Seleksi & Laporan
    Route::get('/hasil-seleksi', [SeleksiController::class, 'index'])->name('hasil-seleksi.index');
    Route::post('/seleksi/proses', [SeleksiController::class, 'prosesSeleksi'])->name('seleksi.proses');

    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

});

// Route logout menggunakan POST sesuai standar Laravel
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'Anda telah logout.');
})->name('logout');

// Include route untuk autentikasi bawaan Laravel
require __DIR__ . '/auth.php';
