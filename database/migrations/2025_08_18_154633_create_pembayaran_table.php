<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->char('id_pembayaran', 20)->primary();
            $table->char('id_transaksi', 28);
            $table->enum('metode', ['tunai', 'non_tunai']);
            $table->decimal('jumlah', 10, 2);
            $table->string('keterangan', 255)->nullable();
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
