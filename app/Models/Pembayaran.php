<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran'; //
    protected $primaryKey = 'id_pembayaran'; //

    protected $fillable = [
        'id_donasi_uang',
        'transaction_id',
        'metode_pembayaran',
        'waktu_bayar',
    ];

    public function donasi_uang(): BelongsTo
    {
        return $this->belongsTo(DonasiUang::class, 'id_donasi_uang', 'id_donasi_uang');
    }
}