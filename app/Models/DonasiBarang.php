<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DonasiBarang extends Model
{
    use HasFactory;

    protected $table = 'donasi_barang';
    protected $primaryKey = 'id_donasi_barang';

    // Pastikan semua field yang diinput dari form masuk ke sini
    protected $fillable = [
        'id_donasi',
        'id_kategori_barang',
        'nama_barang',
        'jumlah_barang',
        'satuan_barang', // Sesuai dengan database Anda
        'keterangan_sasaran',
    ];

    // Relasi ke Donasi Utama
    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class, 'id_donasi', 'id_donasi');
    }

    // Relasi ke Kategori Barang
    public function kategori_barang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori_barang', 'id_kategori_barang');
    }

    // Relasi ke SEMUA histori pelacakan (untuk Timeline)
    public function pelacakan(): HasMany
    {
        return $this->hasMany(PelacakanDonasiBarang::class, 'id_donasi_barang', 'id_donasi_barang')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * SOLUSI JENIUS: 
     * Relasi ke pelacakan TERBARU saja. 
     * Membantu mempermudah akses lokasi terkini di Peta tanpa perlu looping di Blade.
     */
    public function latestPelacakan(): HasOne
    {
        return $this->hasOne(PelacakanDonasiBarang::class, 'id_donasi_barang', 'id_donasi_barang')
                    ->latestOfMany();
    }
}