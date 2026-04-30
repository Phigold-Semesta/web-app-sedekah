<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; 
    protected $primaryKey = 'id_user'; 

    /**
     * Sesuai gambar database bos: kolomnya 'username', bukan 'email'.
     */
    protected $fillable = [
        'nama_user',
        'username', // Diperbaiki dari 'email'
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Beritahu Laravel bahwa kita menggunakan kolom 'username' untuk login.
     */
    public function username()
    {
        return 'username';
    }

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'id_user', 'id_user');
    }

    public function audit_log(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'id_user', 'id_user');
    }
}