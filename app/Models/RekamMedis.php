<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $guarded = [];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Menyimpan data pemeriksaan awal (suhu, TD, BB) oleh Perawat & mengubah status kunjungan ke 'antri_dokter'.
     */
    public static function simpanPemeriksaanAwal(Kunjungan $kunjungan, array $data): self
    {
        return DB::transaction(function () use ($kunjungan, $data) {
            $rekamMedis = self::updateOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                [
                    'suhu' => $data['suhu'],
                    'tekanan_darah' => $data['tekanan_darah'],
                    'berat_badan' => $data['berat_badan'],
                ]
            );

            $kunjungan->update([
                'status' => 'antri_dokter',
            ]);

            return $rekamMedis;
        });
    }

    /**
     * Menyimpan hasil pemeriksaan Dokter (keluhan & diagnosa), resep obat, kalkulasi biaya tagihan kasir, dan pengoperan ke Kasir.
     */
    public static function simpanPemeriksaanDokter(Kunjungan $kunjungan, array $validatedData, array $requestParams = []): self
    {
        return DB::transaction(function () use ($kunjungan, $validatedData, $requestParams) {
            // Simpan / Update Rekam Medis
            $rekamMedis = self::updateOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                [
                    'user_id' => auth()->id(),
                    'keluhan' => $validatedData['keluhan'],
                    'diagnosa' => $validatedData['diagnosa'],
                ]
            );

            // Simpan Resep Obat
            ResepObat::where('kunjungan_id', $kunjungan->id)->delete();
            $totalBiayaObat = 0;

            if (isset($requestParams['obat_id']) && is_array($requestParams['obat_id'])) {
                foreach ($requestParams['obat_id'] as $index => $obatId) {
                    if (!empty($obatId)) {
                        $qty = $validatedData['jumlah'][$index] ?? 1;
                        $aturan = $validatedData['aturan_pakai'][$index] ?? '3x1 Sehari Sesudah Makan';

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

            // Hitung & Update Tagihan Kasir
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

            // Update status kunjungan ke 'kasir' (Menunggu Pembayaran)
            $kunjungan->update([
                'status' => 'kasir',
            ]);

            return $rekamMedis;
        });
    }
}
