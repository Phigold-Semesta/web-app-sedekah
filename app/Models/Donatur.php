<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Donatur extends Model
{
    protected $table = 'donatur'; 
    protected $primaryKey = 'id_donatur'; 

    protected $fillable = [
        'nama_donatur',
        'no_hp',
        'alamat',
    ];

    /**
     * Relasi ke tabel Kunjungan
     * Donatur memiliki banyak kunjungan.
     */
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_donatur', 'id_donatur');
    }

    /**
     * DISEMPURNAKAN: Relasi ke Donasi Uang
     * Karena jalurnya jauh (Donatur -> Kunjungan -> Donasi -> DonasiUang), 
     * kita tetap definisikan relasi agar tidak error saat dipanggil (eager loading),
     * namun penanganannya lebih optimal jika menggunakan collection di Controller.
     */
    public function donasi_uang()
    {
        // Tetap menggunakan hasMany ke DonasiUang sebagai placeholder relasi, 
        // Namun kunci utama data ini ada di fungsi donatur_show() pada Controller
        return $this->hasMany(DonasiUang::class, 'id_donasi', 'id_donasi');
    }

    /**
     * DISEMPURNAKAN: Relasi ke Donasi Barang
     */
    public function donasi_barang()
    {
        return $this->hasMany(DonasiBarang::class, 'id_donasi', 'id_donasi');
    }

    /**
     * FITUR TAMBAHAN: Helper untuk menghitung total donasi secara akurat 
     * melewati jalur: Donatur -> Kunjungan -> Donasi -> Donasi Uang/Barang
     */
    public function scopeWithTotalDonasi($query)
    {
        return $query->addSelect(['donasi_uang_count' => \App\Models\DonasiUang::selectRaw('count(*)')
            ->whereIn('id_donasi', function($q) {
                $q->select('id_donasi')->from('donasi')
                  ->whereIn('id_kunjungan', function($sq) {
                      $sq->select('id_kunjungan')->from('kunjungan')
                         ->whereColumn('id_donatur', 'donatur.id_donatur');
                  });
            })
        ])->addSelect(['donasi_barang_count' => \App\Models\DonasiBarang::selectRaw('count(*)')
            ->whereIn('id_donasi', function($q) {
                $q->select('id_donasi')->from('donasi')
                  ->whereIn('id_kunjungan', function($sq) {
                      $sq->select('id_kunjungan')->from('kunjungan')
                         ->whereColumn('id_donatur', 'donatur.id_donatur');
                  });
            })
        ]);
    }

    /**
     * DISEMPURNAKAN: Menambahkan relasi HasManyThrough jika ingin mencoba 
     * akses langsung dari Donatur ke Donasi (Opsional tapi berguna untuk performa)
     */
    public function donasi()
    {
        return $this->hasManyThrough(
            Donasi::class,
            Kunjungan::class,
            'id_donatur', // Foreign key di tabel kunjungan
            'id_kunjungan', // Foreign key di tabel donasi
            'id_donatur', // Local key di tabel donatur
            'id_kunjungan' // Local key di tabel kunjungan
        );
    }
}