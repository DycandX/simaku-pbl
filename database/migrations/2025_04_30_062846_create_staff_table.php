<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menentukan nama tabel 'staff' (bukan 'staffs')
        Schema::create('staff', function (Blueprint $table) {
            $table->id(); // Kolom ID
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('nip');
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
