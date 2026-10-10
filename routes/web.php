<?php

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

    Route::get('/pasien', function () {
        return view('pasien.index');
    })->name('pasien.index');

    Route::get('/kunjungan', function () {
        return view('kunjungan.index');
    })->name('kunjungan.index');

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
