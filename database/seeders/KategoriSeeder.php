<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Makanan'],
            ['nama' => 'Minuman'],
            ['nama' => 'Obat-obatan'],
            ['nama' => 'Kosmetik'],
            ['nama' => 'Elektronik'],
            ['nama' => 'Perlengkapan Rumah'],
            ['nama' => 'Pakaian'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate($kategori);
        }
    }
}
