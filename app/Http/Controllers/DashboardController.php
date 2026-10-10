<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Tagihan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan rangkuman statistik dan antrian di Dashboard Klinik.
     */
    public function __invoke()
    {
        $today = Carbon::today();

        // Statistik Utama
        $totalPasien = Pasien::count();
        $antrianHariIni = Kunjungan::whereDate('tanggal_kunjungan', $today)->count();
        $pasienSelesaiHariIni = Kunjungan::whereDate('tanggal_kunjungan', $today)->where('status', 'selesai')->count();
        $pendapatanHariIni = Tagihan::whereHas('kunjungan', function ($q) use ($today) {
            $q->whereDate('tanggal_kunjungan', $today);
        })->where('status_bayar', 'lunas')->sum('total_tagihan');

        // Status Antrian Aktif Hari Ini per Stasiun Pelayanan
        $stasiunTriage = Kunjungan::whereDate('tanggal_kunjungan', $today)->where('status', 'antri_triage')->count();
        $stasiunDokter = Kunjungan::whereDate('tanggal_kunjungan', $today)->whereIn('status', ['antri_dokter', 'periksa'])->count();
        $stasiunKasir = Kunjungan::whereDate('tanggal_kunjungan', $today)->where('status', 'kasir')->count();
        $stasiunApotek = Kunjungan::whereDate('tanggal_kunjungan', $today)->where('status', 'apotek')->count();

        // Daftar Antrian Terkini Hari Ini
        $antrianTerbaru = Kunjungan::with(['pasien', 'tagihan'])
            ->whereDate('tanggal_kunjungan', $today)
            ->latest('id')
            ->take(8)
            ->get();

        // Daftar Obat dengan Stok Menipis (Stok <= 10)
        $obatStokMenipis = Obat::where('stok', '<=', 10)->orderBy('stok', 'asc')->take(5)->get();

        return view('dashboard', compact(
            'totalPasien',
            'antrianHariIni',
            'pasienSelesaiHariIni',
            'pendapatanHariIni',
            'stasiunTriage',
            'stasiunDokter',
            'stasiunKasir',
            'stasiunApotek',
            'antrianTerbaru',
            'obatStokMenipis'
        ));
    }
}
