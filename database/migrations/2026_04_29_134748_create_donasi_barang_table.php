<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi_barang', function (Blueprint $table) {
            $table->id('id_donasi_barang');
            // Relasi ke donasi dan kategori_barang
            $table->foreignId('id_donasi')->constrained('donasi', 'id_donasi')->onDelete('cascade');
            $table->foreignId('id_kategori_barang')->constrained('kategori_barang', 'id_kategori_barang');
            $table->string('nama_barang');
            $table->integer('jumlah_barang');
            $table->string('satuan_barang');
            $table->text('keterangan_sasaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi_barang');
    }
};