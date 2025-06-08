<?php
// 6. Migration untuk tabel ukt_semester (DIPERBARUI)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ukt_semester', function (Blueprint $table) {
            $table->id(); // Primary key: bigIncrements

            // PERUBAHAN: Pakai id_enrollment (bukan id_mahasiswa langsung)
            $table->foreignId('id_enrollment')->constrained('enrollment_mahasiswa', 'id')->onDelete('cascade');

            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->foreignId('id_periode_pembayaran')->constrained('periode_pembayaran')->onDelete('cascade');
            $table->decimal('jumlah_ukt', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ukt_semester');
    }
};
