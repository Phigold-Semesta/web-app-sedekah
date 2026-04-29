<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi_uang', function (Blueprint $table) {
            $table->id('id_donasi_uang');
            // Relasi ke induk donasi
            $table->foreignId('id_donasi')->constrained('donasi', 'id_donasi')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->string('order_id')->unique();
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi_uang');
    }
};