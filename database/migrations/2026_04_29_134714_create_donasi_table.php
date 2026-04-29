<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi', function (Blueprint $table) {
            $table->id('id_donasi');
            // Relasi ke kunjungan dan user
            $table->foreignId('id_kunjungan')->constrained('kunjungan', 'id_kunjungan')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->date('tgl_donasi');
            $table->string('status_donasi');
            $table->string('bukti_donasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};