<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanAwalController extends Controller
{
    /**
     * Menampilkan daftar pasien yang antri untuk pemeriksaan awal (suhu, TD, BB).
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pasien', 'rekamMedis']);

        // Default tampilkan pasien yang antri pemeriksaan awal (triage)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'antri_triage');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $kunjungans = $query->latest('tanggal_kunjungan')->paginate(10);

        return view('pemeriksaan-awal.index', compact('kunjungans'));
    }

    /**
     * Menampilkan form input pemeriksaan awal (Suhu, Tekanan Darah, Berat Badan) oleh Perawat.
     */
    public function create(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien', 'rekamMedis']);
        $rekamMedis = $kunjungan->rekamMedis ?? new RekamMedis();

        return view('pemeriksaan-awal.create', compact('kunjungan', 'rekamMedis'));
    }

    /**
     * Menyimpan data pemeriksaan awal dan memperbarui status antrian ke 'antri_dokter'.
     */
    public function store(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'suhu' => 'required|numeric|min:30|max:45',
            'tekanan_darah' => 'required|string|max:20',
            'berat_badan' => 'required|numeric|min:1|max:300',
        ]);

        RekamMedis::simpanPemeriksaanAwal($kunjungan, $validated);

        return redirect()->route('pemeriksaan-awal.index')
            ->with('success', 'Pemeriksaan Awal antrian ' . $kunjungan->no_antrian . ' berhasil disimpan! Pasien diserahkan ke Dokter.');
    }
}
