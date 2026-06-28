<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';

    protected $fillable = [
        'id_kunjungan', // Opsional, bisa NULL jika rating dari donasi online
        'id_donatur',   // Jangkar utama untuk semua donatur
        'skor_rating',
        'saran',
        'tanggapan',    // Field baru untuk balasan Admin/Direktur
        'status',       // Status feedback (pending/terbalas)
    ];

    /**
     * Relasi ke Donatur (Wajib, karena setiap rating pasti berasal dari donatur)
     */
    public function donatur(): BelongsTo
    {
        return $this->belongsTo(Donatur::class, 'id_donatur', 'id_donatur');
    }

    /**
     * Relasi ke Kunjungan (Opsional, hanya jika rating diberikan setelah kunjungan onsite)
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }
}