<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang'; //
    protected $primaryKey = 'id_kategori_barang'; //

    protected $fillable = [
        'nama_kategori',
    ];

    public function donasi_barang(): HasMany
    {
        return $this->hasMany(DonasiBarang::class, 'id_kategori_barang', 'id_kategori_barang');
    }
}