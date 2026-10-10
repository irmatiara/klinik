<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\ResepObat;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    /**
     * Menampilkan daftar pasien antri dokter dan riwayat rekam medis.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pasien', 'rekamMedis', 'resepObats.obat']);

        // Tampilkan antrian dokter (status = antri_dokter)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['antri_dokter', 'periksa']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $kunjungans = $query->latest('tanggal_kunjungan')->paginate(10);

        return view('rekam-medis.index', compact('kunjungans'));
    }

    /**
     * Form pemeriksaan dokter & penulisan rekam medis + resep obat.
     */
    public function create(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien', 'rekamMedis', 'resepObats.obat']);
        $rekamMedis = $kunjungan->rekamMedis ?? new RekamMedis();
        $obats = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

        return view('rekam-medis.create', compact('kunjungan', 'rekamMedis', 'obats'));
    }

    /**
     * Menyimpan hasil pemeriksaan dokter, resep obat, dan update tagihan ke Kasir.
     */
    public function store(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'obat_id' => 'nullable|array',
            'obat_id.*' => 'exists:obat,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'integer|min:1',
            'aturan_pakai' => 'nullable|array',
            'aturan_pakai.*' => 'string|max:255',
        ]);

        DB::transaction(function () use ($request, $kunjungan, $validated) {
            // 1. Simpan / Update Rekam Medis
            RekamMedis::updateOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                [
                    'user_id' => auth()->id(),
                    'keluhan' => $validated['keluhan'],
                    'diagnosa' => $validated['diagnosa'],
                ]
            );

            // 2. Simpan Resep Obat
            ResepObat::where('kunjungan_id', $kunjungan->id)->delete();
            $totalBiayaObat = 0;

            if ($request->has('obat_id') && is_array($request->obat_id)) {
                foreach ($request->obat_id as $index => $obatId) {
                    if (!empty($obatId)) {
                        $qty = $validated['jumlah'][$index] ?? 1;
                        $aturan = $validated['aturan_pakai'][$index] ?? '3x1 Sehari Sesudah Makan';

                        ResepObat::create([
                            'kunjungan_id' => $kunjungan->id,
                            'obat_id' => $obatId,
                            'jumlah' => $qty,
                            'aturan_pakai' => $aturan,
                        ]);

                        $obat = Obat::find($obatId);
                        if ($obat) {
                            $totalBiayaObat += ($obat->harga * $qty);
                        }
                    }
                }
            }

            // 3. Hitung & Update Tagihan Kasir
            $tagihan = Tagihan::firstOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                ['biaya_layanan' => 50000, 'biaya_obat' => 0, 'total_tagihan' => 50000, 'status_bayar' => 'belum_lunas']
            );

            $biayaLayanan = $tagihan->biaya_layanan;
            $totalTagihanBaru = $biayaLayanan + $totalBiayaObat;

            $tagihan->update([
                'biaya_obat' => $totalBiayaObat,
                'total_tagihan' => $totalTagihanBaru,
            ]);

            // 4. Update status kunjungan ke 'kasir' (Menunggu Pembayaran)
            $kunjungan->update([
                'status' => 'kasir',
            ]);
        });

        return redirect()->route('rekam-medis.index')
            ->with('success', 'Pemeriksaan Rekam Medis & Resep antrian ' . $kunjungan->no_antrian . ' berhasil disimpan! Pasien dioper ke Kasir.');
    }
}
