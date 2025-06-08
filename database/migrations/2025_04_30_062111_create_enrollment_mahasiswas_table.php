<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_mahasiswa', function (Blueprint $table) {
            $table->id(); // Primary key

            // Menambahkan kolom id_mahasiswa sebagai foreign key
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->cascadeOnDelete();

            // PERUBAHAN: Langsung ke id_program_studi
            $table->foreignId('id_program_studi')->constrained('program_studi', 'id')->cascadeOnDelete();

            // TAMBAHAN: Golongan UKT
            $table->foreignId('id_golongan_ukt')->constrained('golongan_ukt')->cascadeOnDelete();

            $table->foreignId('id_kelas')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('id_tingkat')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('id_tahun_akademik')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_mahasiswa');
    }
};
