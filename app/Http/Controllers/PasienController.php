<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    /**
     * Menampilkan data pasien.
     */
    public function index(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $pasiens = $query->latest()->paginate(10);

        return view('pasien.index', compact('pasiens'));
    }

    /**
     * Form pendaftaran pasien baru.
     */
    public function create()
    {
        // Generate nomor rekam medis
        $lastPasien = Pasien::latest('id')->first();
        $nextId = $lastPasien ? $lastPasien->id + 1 : 1;
        $autoNoRm = 'RM-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('pasien.create', compact('autoNoRm'));
    }

    /**
     * Menyimpan data pasien baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_rm' => 'required|string|unique:pasien,no_rm',
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $pasien = Pasien::create($validated);

        // Jika pendaftaran pasien baru dilakukan langsung dari form antrian
        if ($request->has('redirect_to_antrian')) {
            return redirect()->route('kunjungan.create', ['pasien_id' => $pasien->id])
                ->with('success', 'Pasien baru berhasil didaftarkan! Silakan lanjutkan pembuatan antrian.');
        }

        return redirect()->route('pasien.index')->with('success', 'Data pasien baru berhasil ditambahkan!');
    }
}
