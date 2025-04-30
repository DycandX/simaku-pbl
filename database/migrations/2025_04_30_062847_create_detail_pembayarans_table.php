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
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('id_aktivitas_pembayaran')->constrained('pembayaran_ukt_semester')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->dateTime('tanggal_pembayaran');
            $table->string('metode_pembayaran');
            $table->string('kode_referensi')->nullable();
            $table->string('bukti_pembayaran_path')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('staff')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pembayarans');
    }
};

