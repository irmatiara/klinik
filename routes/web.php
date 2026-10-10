<?php

use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemeriksaanAwalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Modul Pasien
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
    Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
    Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');

    // Route Registrasi & Antrian Pasien
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::get('/kunjungan/{kunjungan}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
    Route::put('/kunjungan/{kunjungan}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::delete('/kunjungan/{kunjungan}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

    // Route Pemeriksaan Awal (Suhu, TD, BB)
    Route::get('/pemeriksaan-awal', [PemeriksaanAwalController::class, 'index'])->name('pemeriksaan-awal.index');
    Route::get('/pemeriksaan-awal/{kunjungan}/create', [PemeriksaanAwalController::class, 'create'])->name('pemeriksaan-awal.create');
    Route::post('/pemeriksaan-awal/{kunjungan}', [PemeriksaanAwalController::class, 'store'])->name('pemeriksaan-awal.store');

    Route::get('/rekam-medis', function () {
        return view('rekam-medis.index');
    })->name('rekam-medis.index');

    Route::get('/obat', function () {
        return view('obat.index');
    })->name('obat.index');

    Route::get('/resep-obat', function () {
        return view('resep-obat.index');
    })->name('resep-obat.index');

    Route::get('/tagihan', function () {
        return view('tagihan.index');
    })->name('tagihan.index');
});

require __DIR__.'/auth.php';
