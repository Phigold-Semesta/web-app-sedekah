<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DonasiUang extends Model
{
    protected $table = 'donasi_uang';
    protected $primaryKey = 'id_donasi_uang';

    protected $fillable = [
        'id_donasi',
        'nominal',
        'order_id',
        'snap_token',
        'status',
    ];

    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class, 'id_donasi', 'id_donasi');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_donasi_uang', 'id_donasi_uang');
    }
}