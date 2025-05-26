<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pengajuan_cicilan', function (Blueprint $table) {
            $table->id(); // auto-increment
            $table->string('nama_lengkap');
            $table->string('nim');
            $table->string('fakultas');
            $table->string('program_studi');
            $table->string('tagihan');
            $table->integer('angsuran');
            $table->string('file_path');
            $table->boolean('status')->default(0); // 1 atau 0
            $table->timestamps();
        });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cicilan');
    }
};
