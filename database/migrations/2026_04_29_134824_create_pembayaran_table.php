<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            // Relasi ke donasi_uang
            $table->foreignId('id_donasi_uang')->constrained('donasi_uang', 'id_donasi_uang')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->dateTime('waktu_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};