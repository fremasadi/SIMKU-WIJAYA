<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produks = [
            [
                'nama_produk' => 'Kerupuk Udang Premium',
                'stok' => 150.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Ikan Tenggiri',
                'stok' => 120.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Bawang',
                'stok' => 200.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Kulit Sapi',
                'stok' => 100.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Singkong',
                'stok' => 180.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Gendar',
                'stok' => 160.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Terasi',
                'stok' => 130.00,
                'satuan' => 'Kg',
            ],
            [
                'nama_produk' => 'Kerupuk Palembang',
                'stok' => 140.00,
                'satuan' => 'Kg',
            ],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}
