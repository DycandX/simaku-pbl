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
        Schema::create('pembayaran_ukt_semester', function (Blueprint $table) {
            $table->id(); // Primary key: bigIncrements
            $table->string('nim'); // foreign key ke mahasiswa
            $table->foreignId('id_ukt_semester')->constrained('ukt_semester')->onDelete('cascade');
            $table->unsignedInteger('nomor_cicilan');
            $table->decimal('nominal_tagihan', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->string('status');
            $table->timestamps();

            // Foreign key ke mahasiswa
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_ukt_semester');
    }
};
