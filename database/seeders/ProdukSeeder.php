<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = [
            [
                'id_produk' => 'MKN001',
                'nama' => 'Nasi Goreng Instan',
                'id_kategori' => 1, // Makanan
                'nomor_bpom' => 'BPOM123456789',
                'harga' => 15000,
                'biaya_produk' => 10000,
                'stok' => 50,
                'batas_stok' => 10,
            ],
            [
                'id_produk' => 'MKN002',
                'nama' => 'Mie Ayam Instan',
                'id_kategori' => 1, // Makanan
                'nomor_bpom' => 'BPOM987654321',
                'harga' => 12000,
                'biaya_produk' => 8000,
                'stok' => 75,
                'batas_stok' => 15,
            ],
            [
                'id_produk' => 'MNM001',
                'nama' => 'Air Mineral 600ml',
                'id_kategori' => 2, // Minuman
                'nomor_bpom' => 'BPOM111222333',
                'harga' => 3000,
                'biaya_produk' => 2000,
                'stok' => 100,
                'batas_stok' => 20,
            ],
            [
                'id_produk' => 'MNM002',
                'nama' => 'Teh Botol 350ml',
                'id_kategori' => 2, // Minuman
                'nomor_bpom' => 'BPOM444555666',
                'harga' => 5000,
                'biaya_produk' => 3500,
                'stok' => 60,
                'batas_stok' => 10,
            ],
            [
                'id_produk' => 'OBT001',
                'nama' => 'Paracetamol 500mg',
                'id_kategori' => 3, // Obat-obatan
                'nomor_bpom' => 'BPOM777888999',
                'harga' => 25000,
                'biaya_produk' => 18000,
                'stok' => 30,
                'batas_stok' => 5,
            ],
            [
                'id_produk' => 'KSM001',
                'nama' => 'Sabun Mandi Herbal',
                'id_kategori' => 4, // Kosmetik
                'nomor_bpom' => 'BPOM000111222',
                'harga' => 8000,
                'biaya_produk' => 5500,
                'stok' => 40,
                'batas_stok' => 8,
            ],
            [
                'id_produk' => 'ELK001',
                'nama' => 'Kabel USB Type-C',
                'id_kategori' => 5, // Elektronik
                'nomor_bpom' => null,
                'harga' => 35000,
                'biaya_produk' => 25000,
                'stok' => 25,
                'batas_stok' => 5,
            ],
            [
                'id_produk' => 'MKN003',
                'nama' => 'Kerupuk Udang',
                'id_kategori' => 1, // Makanan
                'nomor_bpom' => 'BPOM333444555',
                'harga' => 18000,
                'biaya_produk' => 12000,
                'stok' => 5, // Low stock
                'batas_stok' => 10,
            ],
            [
                'id_produk' => 'MNM003',
                'nama' => 'Jus Jeruk 250ml',
                'id_kategori' => 2, // Minuman
                'nomor_bpom' => 'BPOM666777888',
                'harga' => 8000,
                'biaya_produk' => 5500,
                'stok' => 0, // Out of stock
                'batas_stok' => 12,
            ],
        ];

        foreach ($produks as $produk) {
            Produk::firstOrCreate(['id_produk' => $produk['id_produk']], $produk);
        }
    }
}
