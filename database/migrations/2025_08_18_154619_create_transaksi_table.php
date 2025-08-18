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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->char('id_transaksi', 28)->primary();
            $table->unsignedBigInteger('id_pengguna');
            $table->char('id_pelanggan', 7);
            $table->date('tanggal');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['menunggu', 'selesai', 'dibatalkan']);
            $table->text('catatan')->nullable();
            $table->decimal('diskon', 10, 2)->nullable();
            $table->decimal('pajak', 10, 2)->nullable();
            $table->integer('biaya_pengiriman');
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
