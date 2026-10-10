<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemeriksaanAwalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route Modul Pasien (Admin, Resepsionis, Perawat, Dokter)
    Route::middleware('role:resepsionis,perawat,dokter')->group(function () {
        Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
        Route::get('/pasien/create', [PasienController::class, 'create'])->name('pasien.create');
        Route::post('/pasien', [PasienController::class, 'store'])->name('pasien.store');
    });

    // Route Registrasi & Antrian Pasien (Admin, Resepsionis, Perawat)
    Route::middleware('role:resepsionis,perawat')->group(function () {
        Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
        Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan/{kunjungan}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan/{kunjungan}', [KunjunganController::class, 'update'])->name('kunjungan.update');
        Route::delete('/kunjungan/{kunjungan}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
    });

    // Route Pemeriksaan Awal (Admin, Perawat)
    Route::middleware('role:perawat')->group(function () {
        Route::get('/pemeriksaan-awal', [PemeriksaanAwalController::class, 'index'])->name('pemeriksaan-awal.index');
        Route::get('/pemeriksaan-awal/{kunjungan}/create', [PemeriksaanAwalController::class, 'create'])->name('pemeriksaan-awal.create');
        Route::post('/pemeriksaan-awal/{kunjungan}', [PemeriksaanAwalController::class, 'store'])->name('pemeriksaan-awal.store');
    });

    // Route Ruang Dokter & Rekam Medis (Admin, Dokter)
    Route::middleware('role:dokter')->group(function () {
        Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
        Route::get('/rekam-medis/{kunjungan}/create', [RekamMedisController::class, 'create'])->name('rekam-medis.create');
        Route::post('/rekam-medis/{kunjungan}', [RekamMedisController::class, 'store'])->name('rekam-medis.store');
    });

    // Route Master Data Obat (Admin, Apoteker, Dokter)
    Route::middleware('role:apoteker,dokter')->group(function () {
        Route::resource('obat', ObatController::class);
    });

    // Route Pembayaran Kasir & Tagihan (Admin, Kasir)
    Route::middleware('role:kasir')->group(function () {
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
        Route::get('/tagihan/{tagihan}/edit', [TagihanController::class, 'edit'])->name('tagihan.edit');
        Route::put('/tagihan/{tagihan}', [TagihanController::class, 'update'])->name('tagihan.update');
    });

    // Route Stasiun Farmasi & Penyerahan Obat (Admin, Apoteker)
    Route::middleware('role:apoteker')->group(function () {
        Route::get('/resep-obat', [FarmasiController::class, 'index'])->name('resep-obat.index');
        Route::post('/resep-obat/{kunjungan}/serahkan', [FarmasiController::class, 'serahkanObat'])->name('resep-obat.serahkan');
    });
});

require __DIR__.'/auth.php';
