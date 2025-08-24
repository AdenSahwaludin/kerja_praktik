<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\Detail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        $user = User::create([
            'nama' => 'Aden Sahwaludin',
            'email' => 'sekha1211@gmail.com',
            'telepon' => '081292596948',
            'role' => 'admin',
            'kata_sandi' => Hash::make('Margamulya1'),
            'terakhir_login' => now(),
        ]);

        // Create example category
        $kategori = Kategori::create([
            'nama' => 'Contoh Kategori',
        ]);

        // Create example product
        $produk = Produk::create([
            'id_kategori' => $kategori->id_kategori,
            'nama' => 'Contoh Produk',
            'gambar' => null,
            'nomor_bpom' => null,
            'harga' => 10000,
            'biaya_produk' => 8000,
            'stok' => 50,
            'batas_stok' => 10,
        ]);

        // Create example customer
        $pelanggan = Pelanggan::create([
            'nama' => 'Contoh Pelanggan',
            'email' => 'pelanggan@example.com',
            'telepon' => '081234567890',
            'kota' => 'Contoh Kota',
            'alamat' => 'Jl. Contoh Alamat No.1',
        ]);

        // Create example transaction
        $transaksi = Transaksi::create([
            'id_pengguna' => $user->id,
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'tanggal' => now(),
            'total_harga' => $produk->harga,
            'status' => 'pending',
            'catatan' => null,
            'diskon' => 0,
            'pajak' => 0,
            'biaya_pengiriman' => 5000,
        ]);

        // Create example payment
        Pembayaran::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'metode' => 'tunai',
            'jumlah' => $transaksi->total_harga,
            'keterangan' => null,
            'tanggal' => now(),
        ]);

        // Create example detail record
        Detail::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_produk' => $produk->id_produk,
            'jumlah' => 1,
            'harga_satuan' => $produk->harga,
        ]);
    }
}
