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
        Schema::create('enrollment_mahasiswa', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nim'); // foreign key ke mahasiswa
            $table->foreignId('id_kelas')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('id_tingkat')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('id_tahun_akademik')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->timestamps();

            // Relasi nim sebagai foreign key ke tabel mahasiswa
            $table->foreign('nim')->references('nim')->on('mahasiswa')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_mahasiswa');
    }
};
