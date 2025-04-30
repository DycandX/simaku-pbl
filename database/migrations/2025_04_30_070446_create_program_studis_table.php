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
        Schema::create('program_studi', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_prodi');
            $table->unsignedBigInteger('id_fakultas'); // Foreign key ke tabel fakultas
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('id_fakultas')->references('id')->on('fakultas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studi');
    }
};
