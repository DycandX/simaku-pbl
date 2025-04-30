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
        Schema::create('golongan_ukt', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedTinyInteger('level')->unique(); // Misal level 1–8, dibuat unique
            $table->decimal('nominal', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->year('tahun_berlaku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golongan_ukt');
    }
};
