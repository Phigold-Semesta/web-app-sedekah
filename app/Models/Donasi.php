<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donasi extends Model
{
    protected $table = 'donasi';
    protected $primaryKey = 'id_donasi';

    protected $fillable = [
        'id_donatur',      
        'id_kunjungan',
        'id_user',
        'jenis_donasi',
        'jumlah',
        'nama_barang',
        'jumlah_barang',
        'satuan',
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

    public function donatur(): BelongsTo
    {
        return $this->belongsTo(Donatur::class, 'id_donatur', 'id_donatur');
    }

    public function donasiUang(): HasOne
    {
        return $this->hasOne(DonasiUang::class, 'id_donasi', 'id_donasi');
    }

    public function donasiBarang(): HasOne
    {
        return $this->hasOne(DonasiBarang::class, 'id_donasi', 'id_donasi');
    }
}