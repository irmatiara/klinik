<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiController extends Controller
{
    /**
     * Menampilkan daftar resep obat pasien yang siap disiapkan/diserahkan oleh Apoteker.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pasien', 'rekamMedis', 'resepObats.obat', 'tagihan']);

        // Antrian apotek (status = apotek)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'apotek');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $kunjungans = $query->latest('tanggal_kunjungan')->paginate(10);

        return view('resep-obat.index', compact('kunjungans'));
    }

    /**
     * Memproses penyerahan obat ke pasien, memotong stok obat otomatis, dan menyelesaiakan pelayanan.
     */
    public function serahkanObat(Request $request, Kunjungan $kunjungan)
    {
        $kunjungan->load(['resepObats.obat', 'pasien']);

        DB::transaction(function () use ($kunjungan) {
            // 1. Potong stok masing-masing obat
            foreach ($kunjungan->resepObats as $resep) {
                if ($resep->obat) {
                    $resep->obat->decrement('stok', $resep->jumlah);
                }
            }

            // 2. Ubah status kunjungan ke 'selesai'
            $kunjungan->update([
                'status' => 'selesai',
            ]);
        });

        return redirect()->route('resep-obat.index')
            ->with('success', 'Obat berhasil diserahkan kepada pasien ' . ($kunjungan->pasien->nama ?? '') . '! Stok obat otomatis terpotong & pelayanan selesai.');
    }
}
