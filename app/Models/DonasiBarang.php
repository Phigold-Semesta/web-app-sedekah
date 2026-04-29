<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonasiBarang extends Model
{
    protected $table = 'donasi_barang'; //
    protected $primaryKey = 'id_donasi_barang'; //

    protected $fillable = [
        'id_donasi',
        'id_kategori_barang',
        'nama_barang',
        'jumlah_barang',
        'satuan_barang',
        'keterangan_sasaran',
    ];

    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class, 'id_donasi', 'id_donasi');
    }

    public function kategori_barang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori_barang', 'id_kategori_barang');
    }
}