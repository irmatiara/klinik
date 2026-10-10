<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $table = 'obat';

    protected $guarded = [];

    public function resepObats(): HasMany
    {
        return $this->hasMany(ResepObat::class, 'obat_id');
    }

    /**
     * Membentuk kode obat otomatis (contoh: OBT-001).
     */
    public static function generateKodeObat(): string
    {
        $latestId = self::max('id') ?? 0;
        return 'OBT-' . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);   // Membentuk kode obat otomatis berdasarkan ID terakhir
    }

    /**
     * Menyimpan data obat baru ke database.
     */
    public static function simpanObat(array $data): self
    {
        return self::create($data);     // untuk menyimpan data obat baru ke database
    }

    /**
     * Memperbarui data obat yang ada.
     */
    public function perbaruiObat(array $data): bool
    {
        return $this->update($data);       // untuk memperbarui data obat yang ada
    }
}
