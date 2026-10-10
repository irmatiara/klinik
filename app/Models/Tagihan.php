<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $guarded = [];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    /**
     * Memproses konfirmasi pembayaran kasir (ubah status_bayar ke lunas & oper pasien ke apotek).
     */
    public function prosesPembayaranKasir(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->update([
                'status_bayar' => 'lunas',
            ]);

            if ($this->kunjungan) {
                $this->kunjungan->update([
                    'status' => 'apotek',
                ]);
            }
        });
    }
}
