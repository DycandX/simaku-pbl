<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('tahun_akademik'); // contoh: 2024/2025
            $table->string('semester');       // contoh: Ganjil / Genap
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status');         // aktif / nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_akademik');
    }
};
