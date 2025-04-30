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
        Schema::create('ukt_semester', function (Blueprint $table) {
            $table->id(); // Primary key: bigIncrements
            $table->string('nim'); // foreign key ke mahasiswa
            $table->foreignId('id_golongan_ukt')->constrained('golongan_ukt')->onDelete('cascade');
            $table->string('status');
            $table->foreignId('id_periode_pembayaran')->constrained('periode_pembayaran')->onDelete('cascade');
            $table->decimal('jumlah_ukt', 15, 2);
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
        Schema::dropIfExists('ukt_semester');
    }
};
