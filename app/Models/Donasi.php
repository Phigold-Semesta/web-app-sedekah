<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donasi extends Model
{
    protected $table = 'donasi'; //
    protected $primaryKey = 'id_donasi'; //

    protected $fillable = [
        'id_kunjungan',
        'id_user',
        'tgl_donasi',
        'status_donasi',
        'bukti_donasi',
    ];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function donasi_uang(): HasOne
    {
        return $this->hasOne(DonasiUang::class, 'id_donasi', 'id_donasi');
    }

    public function donasi_barang(): HasOne
    {
        return $this->hasOne(DonasiBarang::class, 'id_donasi', 'id_donasi');
    }
}