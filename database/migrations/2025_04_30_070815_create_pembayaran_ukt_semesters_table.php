<?php
// 7. Migration untuk tabel pembayaran_ukt_semester (DIPERBARUI)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_ukt_semester', function (Blueprint $table) {
            $table->id(); // Primary key: bigIncrements

            // PERUBAHAN: Pakai id_enrollment
            $table->foreignId('id_enrollment')->constrained('enrollment_mahasiswa', 'id')->onDelete('cascade');

            $table->foreignId('id_ukt_semester')->constrained('ukt_semester')->onDelete('cascade');

            // PERUBAHAN: Ganti nomor_cicilan dengan id_jenis_pembayaran
            $table->foreignId('id_jenis_pembayaran')->constrained('jenis_pembayaran')->onDelete('cascade');

            $table->unsignedInteger('total_cicilan')->default(1); // Total cicilan yang harus dibayar
            $table->decimal('nominal_tagihan', 15, 2);
            $table->date('tanggal_jatuh_tempo');

            $table->enum('status', ['belum_bayar', 'terbayar', 'cancelled', 'overdue'])->default('belum_bayar');

            // TAMBAHAN: Referensi ke pengajuan cicilan (jika ada)
            $table->foreignId('id_pengajuan_cicilan')->nullable()->constrained('pengajuan_cicilan')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_ukt_semester');
    }
};