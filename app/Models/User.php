<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    // Nama tabel sesuai di phpMyAdmin (image_1ae29e.png)
    protected $table = 'user'; 

    // Primary key sesuai di phpMyAdmin (image_1ae29e.png)
    protected $primaryKey = 'id_user'; 

    /**
     * Properti $fillable disesuaikan dengan struktur kolom di database.
     * 'nama_user' digunakan agar sinkron dengan database (image_1ae29e.png).
     */
    protected $fillable = [
        'nama_user', 
        'username', 
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts ditambahkan untuk memastikan created_at dan updated_at 
     * diperlakukan sebagai objek Carbon/Datetime secara otomatis.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Beritahu Laravel bahwa kita menggunakan kolom 'username' untuk login.
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Relasi ke tabel Donasi tetap dipertahankan.
     * Menggunakan 'id_user' sebagai foreign key sesuai primary key model ini.
     */
    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke tabel AuditLog tetap dipertahankan.
     * Menggunakan 'id_user' sebagai foreign key.
     */
    public function audit_log(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'id_user', 'id_user');
    }
}