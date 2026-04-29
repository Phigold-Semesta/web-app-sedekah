<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kunjungan extends Model
{
    protected $table = 'kunjungan'; //
    protected $primaryKey = 'id_kunjungan'; //

    protected $fillable = [
        'id_donatur',
        'tgl_kunjungan',
        'tujuan_kunjungan',
    ];

    public function donatur(): BelongsTo
    {
        return $this->belongsTo(Donatur::class, 'id_donatur', 'id_donatur');
    }

    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class, 'id_kunjungan', 'id_kunjungan');
    }

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'id_kunjungan', 'id_kunjungan');
    }
}