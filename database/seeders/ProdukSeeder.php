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
                'nama_produk' => 'Sepatu Kulit Pria',
                'stok' => 22.00,
                'satuan' => 'Pasang',
            ],
            [
                'nama_produk' => 'Sepatu Kulit Wanita',
                'stok' => 16.00,
                'satuan' => 'Pasang',
            ],
            [
                'nama_produk' => 'Tas Kulit Selempang',
                'stok' => 8.00,
                'satuan' => 'Pcs',
            ],
            [
                'nama_produk' => 'Tas Kulit Ransel',
                'stok' => 5.00,
                'satuan' => 'Pcs',
            ],
            [
                'nama_produk' => 'Dompet Kulit Pria',
                'stok' => 20.00,
                'satuan' => 'Pcs',
            ],
            [
                'nama_produk' => 'Dompet Kulit Wanita',
                'stok' => 12.00,
                'satuan' => 'Pcs',
            ],
            [
                'nama_produk' => 'Ikat Pinggang Kulit',
                'stok' => 26.00,
                'satuan' => 'Pcs',
            ],
            [
                'nama_produk' => 'Jaket Kulit',
                'stok' => 5.00,
                'satuan' => 'Pcs',
            ],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}
