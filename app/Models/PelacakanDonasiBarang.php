<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelacakanDonasiBarang extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'pelacakan_donasi_barang';

    // Menentukan Primary Key kustom (bukan 'id')
    protected $primaryKey = 'id_pelacakan';

    // Kolom-kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'id_donasi_barang',
        'status_pelacakan',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi Balik (Belongs To) ke tabel Donasi_Barang
     * Setiap 1 riwayat pelacakan ini PASTI milik 1 Donasi Barang.
     */
    public function donasiBarang()
    {
        return $this->belongsTo(DonasiBarang::class, 'id_donasi_barang', 'id_donasi_barang');
    }
}