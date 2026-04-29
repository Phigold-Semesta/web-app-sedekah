<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id('id_kunjungan');
            // Relasi ke tabel donatur
            $table->foreignId('id_donatur')->constrained('donatur', 'id_donatur')->onDelete('cascade');
            $table->date('tgl_kunjungan');
            $table->string('tujuan_kunjungan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};