<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Menampilkan daftar master data obat.
     */
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_obat', 'like', "%{$search}%")
                ->orWhere('kode_obat', 'like', "%{$search}%");
        }

        $obats = $query->orderBy('nama_obat')->paginate(10);

        return view('obat.index', compact('obats'));
    }

    /**
     * Form tambah master obat baru.
     */
    public function create()
    {
        // Generate otomatis kode obat, misal: OBT-001
        $latestId = Obat::max('id') ?? 0;
        $autoKodeObat = 'OBT-' . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);

        return view('obat.create', compact('autoKodeObat'));
    }

    /**
     * Menyimpan data obat ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Obat::create($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat ' . $validated['nama_obat'] . ' berhasil ditambahkan!');
    }

    /**
     * Form edit data obat.
     */
    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    /**
     * Update data obat di database.
     */
    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|unique:obat,kode_obat,' . $obat->id,
            'nama_obat' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $obat->update($validated);

        return redirect()->route('obat.index')
            ->with('success', 'Data obat ' . $validated['nama_obat'] . ' berhasil diperbarui!');
    }

    /**
     * Hapus data obat dari database.
     */
    public function destroy(Obat $obat)
    {
        $namaObat = $obat->nama_obat;
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('success', 'Data obat ' . $namaObat . ' berhasil dihapus!');
    }
}
