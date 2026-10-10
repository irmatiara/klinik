<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $guarded = [];

    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'pasien_id');
    }

    /**
     * Membentuk nomor rekam medis otomatis (contoh: RM-0001).
     */
    public static function generateNoRm(): string
    {
        $lastPasien = self::latest('id')->first();
        $nextId = $lastPasien ? $lastPasien->id + 1 : 1;
        return 'RM-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);      // Membentuk nomor rekam medis otomatis berdasarkan ID terakhir
    }

    /**
     * Menyimpan data pasien baru ke database.
     */
    public static function simpanPasien(array $data): self
    {
        return self::create($data);     // untuk menyimpan data pasien baru ke database
    }
}
