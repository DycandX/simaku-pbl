<?php
// 5. Migration untuk tabel pengajuan_cicilan (DIPERBARUI)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_cicilan', function (Blueprint $table) {
            $table->id(); // auto-increment

            // PERUBAHAN: Pakai id_enrollment dan id_ukt_semester
            $table->foreignId('id_enrollment')->constrained('enrollment_mahasiswa', 'id')->onDelete('cascade');
            $table->foreignId('id_ukt_semester')->constrained('ukt_semester')->onDelete('cascade');

            // Data yang diajukan
            $table->integer('jumlah_angsuran_diajukan');
            $table->integer('jumlah_angsuran_disetujui')->nullable();
            $table->text('alasan_pengajuan')->nullable();

            // File pendukung
            $table->string('file_path');

            // Status dan approval
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('staff', 'id')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cicilan');
    }
};