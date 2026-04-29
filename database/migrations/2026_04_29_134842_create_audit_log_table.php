<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id('id_log');
            // Relasi ke user penginput
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->string('aksi_log');
            $table->dateTime('waktu_log');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};