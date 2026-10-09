<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResepObat extends Model
{
    protected $table = 'resep_obat';

    protected $guarded = [];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
