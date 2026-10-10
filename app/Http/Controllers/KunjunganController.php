<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    /**
     * Menampilkan daftar antrian pendaftaran kunjungan pasien.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with('pasien');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nama pasien / No. RM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $kunjungans = $query->latest('tanggal_kunjungan')->paginate(10);

        return view('kunjungan.index', compact('kunjungans'));
    }

    /**
     * Menampilkan form pendaftaran antrian baru & meng-generate nomor antrian.
     */
    public function create(Request $request)
    {
        // Pembentukan nomor antrian otomatis berdasarkan jumlah kunjungan hari ini
        $today = Carbon::today();
        $jumlahAntrianHariIni = Kunjungan::whereDate('tanggal_kunjungan', $today)->count();
        $nextNo = $jumlahAntrianHariIni + 1;
        $autoNoAntrian = 'A-' . str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        // Mengambil data pasien terdaftar
        $pasiens = Pasien::orderBy('nama')->get();
        $selectedPasienId = $request->query('pasien_id');

        return view('kunjungan.create', compact('pasiens', 'autoNoAntrian', 'selectedPasienId'));
    }

    /**
     * Menyimpan registrasi antrian ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'no_antrian' => 'required|string',
            'biaya_layanan' => 'required|numeric|min:0',
        ]);

        // Simpan data kunjungan dengan status awal: antri_triage
        $kunjungan = Kunjungan::create([
            'pasien_id' => $validated['pasien_id'],
            'no_antrian' => $validated['no_antrian'],
            'tanggal_kunjungan' => Carbon::now(),
            'status' => 'antri_triage',
        ]);

        // Buat data awal tagihan kasir (biaya administrasi / konsul)
        Tagihan::create([
            'kunjungan_id' => $kunjungan->id,
            'biaya_layanan' => $validated['biaya_layanan'],
            'biaya_obat' => 0,
            'total_tagihan' => $validated['biaya_layanan'],
            'status_bayar' => 'belum_lunas',
        ]);

        // Redirect langsung kembali ke halaman utama Kunjungan
        return redirect()->route('kunjungan.index')
            ->with('success', 'Registrasi antrian ' . $validated['no_antrian'] . ' berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit status / data antrian kunjungan.
     */
    public function edit(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien');
        return view('kunjungan.edit', compact('kunjungan'));
    }

    /**
     * Memperbarui data antrian kunjungan di database.
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'no_antrian' => 'required|string|max:20',
            'tanggal_kunjungan' => 'required|date',
            'status' => 'required|in:antri_triage,antri_dokter,periksa,kasir,apotek,selesai,batal',
        ]);

        $kunjungan->update($validated);

        return redirect()->route('kunjungan.index')
            ->with('success', 'Data antrian kunjungan berhasil diperbarui!');
    }

    /**
     * Menghapus data antrian kunjungan.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();

        return redirect()->route('kunjungan.index')
            ->with('success', 'Data antrian kunjungan berhasil dihapus!');
    }
}
