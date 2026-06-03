<?php

namespace App\Models;

// Perlu ditambahkan untuk otentikasi
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Donatur extends Authenticatable
{
    use Notifiable;

    protected $table = 'donatur'; 
    protected $primaryKey = 'id_donatur'; 

    // Ditambahkan email dan password agar bisa diisi (mass assignment)
    protected $fillable = [
        'nama_donatur',
        'no_hp',
        'alamat',
        'email',
        'password',
    ];

    // Password disembunyikan agar tidak muncul saat model di-serialize
    protected $hidden = [
        'password',
        'remember_token',
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
     */
    public function donasi_uang()
    {
        return $this->hasManyThrough(
            DonasiUang::class,
            Donasi::class,
            'id_kunjungan', 
            'id_donasi',    
            'id_donatur',   
            'id_donasi'     
        )->whereIn('donasi.id_kunjungan', function($query) {
            $query->select('id_kunjungan')->from('kunjungan')->whereColumn('id_donatur', 'donatur.id_donatur');
        });
    }

    /**
     * DISEMPURNAKAN: Relasi ke Donasi Barang
     */
    public function donasi_barang()
    {
        return $this->hasManyThrough(
            DonasiBarang::class,
            Donasi::class,
            'id_kunjungan', 
            'id_donasi',    
            'id_donatur',   
            'id_donasi'     
        )->whereIn('donasi.id_kunjungan', function($query) {
            $query->select('id_kunjungan')->from('kunjungan')->whereColumn('id_donatur', 'donatur.id_donatur');
        });
    }

    /**
     * FITUR TAMBAHAN: Helper untuk menghitung total donasi secara akurat 
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
     * DISEMPURNAKAN: Menambahkan relasi HasManyThrough akses langsung Donatur ke Donasi
     */
    public function donasi()
    {
        return $this->hasManyThrough(
            Donasi::class,
            Kunjungan::class,
            'id_donatur', 
            'id_kunjungan', 
            'id_donatur', 
            'id_kunjungan' 
        );
    }
}