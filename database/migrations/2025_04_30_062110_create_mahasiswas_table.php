<?php
// 1. Migration untuk tabel mahasiswa (TANPA id_user)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('nim')->unique(); // NIM tetap ada tapi bukan foreign key
            $table->string('nama_lengkap');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};