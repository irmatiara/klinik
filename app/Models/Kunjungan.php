<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
