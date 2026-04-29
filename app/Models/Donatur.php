<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donatur extends Model
{
    protected $table = 'donatur'; //
    protected $primaryKey = 'id_donatur'; //

    protected $fillable = [
        'nama_donatur',
        'no_hp',
        'alamat',
    ];

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_donatur', 'id_donatur');
    }
}