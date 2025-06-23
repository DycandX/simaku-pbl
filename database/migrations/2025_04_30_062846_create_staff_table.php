<?php
// 2. Migration untuk tabel staff (TANPA id_user)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('nip')->unique(); // NIP tetap ada tapi bukan foreign key
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};