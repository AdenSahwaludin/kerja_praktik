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
        Schema::create('produk', function (Blueprint $table) {
            $table->char('id_produk', 13)->primary();
            $table->tinyInteger('id_kategori')->unsigned();
            $table->string('nama', 100);
            $table->string('gambar', 255)->nullable();
            $table->string('nomor_bpom', 50)->nullable();
            $table->decimal('harga', 10, 2);
            $table->decimal('biaya_produk', 10, 2);
            $table->integer('stok');
            $table->integer('batas_stok');
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
