<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $guarded = [];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function rekamMedis(): HasOne
    {
        return $this->hasOne(RekamMedis::class, 'kunjungan_id');
    }

    public function resepObats(): HasMany
    {
        return $this->hasMany(ResepObat::class, 'kunjungan_id');
    }

    public function tagihan(): HasOne
    {
        return $this->hasOne(Tagihan::class, 'kunjungan_id');
    }

    /**
     * Membentuk nomor antrian otomatis berdasarkan kunjungan hari ini (contoh: A-001).
     */
    public static function generateNoAntrian(): string
    {
        $today = Carbon::today();   // Mendapatkan tanggal hari ini tanpa waktu
        $jumlahAntrianHariIni = self::whereDate('tanggal_kunjungan', $today)->count();
        $nextNo = $jumlahAntrianHariIni + 1;
        return 'A-' . str_pad($nextNo, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Menyimpan registrasi antrian kunjungan baru dan membuat tagihan kasir awal secara otomatis.
     */
    public static function registrasiAntrian(array $data): self
    {
        return DB::transaction(function () use ($data) {
            $kunjungan = self::create([
                'pasien_id' => $data['pasien_id'],
                'no_antrian' => $data['no_antrian'],
                'tanggal_kunjungan' => Carbon::now(),   // Menyimpan tanggal dan waktu saat ini
                'status' => 'antri_triage',
            ]);

            Tagihan::create([
                'kunjungan_id' => $kunjungan->id,
                'biaya_layanan' => $data['biaya_layanan'],
                'biaya_obat' => 0,
                'total_tagihan' => $data['biaya_layanan'],
                'status_bayar' => 'belum_lunas',
            ]);

            return $kunjungan;
        });
    }

    /**
     * Memperbarui data antrian kunjungan.
     */
    public function perbaruiAntrian(array $data): bool
    {
        return $this->update($data);    // untuk memperbarui data antrian kunjungan, termasuk no_antrian, tanggal_kunjungan, dan status
    }

    /**
     * Memproses penyerahan resep obat di farmasi, memotong stok obat otomatis, dan menyelesaikan alur pelayanan.
     */
    public function serahkanObatDanSelesaikan(): void
    {
        $this->loadMissing(['resepObats.obat']);

        DB::transaction(function () {
            foreach ($this->resepObats as $resep) {
                if ($resep->obat) {
                    $resep->obat->decrement('stok', $resep->jumlah);
                }
            }

            $this->update([
                'status' => 'selesai',
            ]);
        });
    }
}
