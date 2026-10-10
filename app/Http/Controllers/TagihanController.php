<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar tagihan pasien & status pembayaran di kasir.
     */
    public function index(Request $request)
    {
        $query = Tagihan::with(['kunjungan.pasien', 'kunjungan.resepObats.obat']);

        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kunjungan.pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $tagihans = $query->latest('id')->paginate(10);

        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * Menampilkan rincian tagihan & form transaksi pembayaran kasir.
     */
    public function edit(Tagihan $tagihan)
    {
        $tagihan->load(['kunjungan.pasien', 'kunjungan.rekamMedis', 'kunjungan.resepObats.obat']);

        return view('tagihan.edit', compact('tagihan'));
    }

    /**
     * Memproses transaksi pembayaran (Ubah status_bayar ke lunas & oper ke Apotek).
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|string',
            'bayar' => 'required|numeric|min:' . $tagihan->total_tagihan,
        ]);

        $tagihan->prosesPembayaranKasir($validated);

        return redirect()->route('tagihan.edit', $tagihan->id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi (LUNAS)! Pasien diserahkan ke Apotek / Farmasi.');
    }
}
