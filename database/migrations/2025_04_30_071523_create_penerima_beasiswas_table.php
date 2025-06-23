<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerima_beasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Foreign key ke tabel mahasiswa
            $table->foreignId('id_beasiswa')->constrained('beasiswa')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamps();

            // Relasi ke mahasiswa (asumsi nim adalah primary key di tabel mahasiswa)
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penerima_beasiswa');
    }
};
