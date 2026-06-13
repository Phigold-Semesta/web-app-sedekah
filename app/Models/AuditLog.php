<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_log'; 
    protected $primaryKey = 'id_log'; 

    protected $fillable = [
        'id_user',
        'aksi_log',
        'deskripsi',  // Ditambahkan agar bisa menyimpan detail aktivitas
        'ip_address', // Ditambahkan untuk pelacakan jejak digital
        'waktu_log',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}