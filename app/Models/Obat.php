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
}
