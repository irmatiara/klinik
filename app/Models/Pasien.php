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
}
