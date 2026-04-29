<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; //
    protected $primaryKey = 'id_user'; //

    protected $fillable = [
        'nama_user',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'id_user', 'id_user');
    }

    public function audit_log(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'id_user', 'id_user');
    }
}