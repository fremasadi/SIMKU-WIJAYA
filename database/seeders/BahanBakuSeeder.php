<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $bahanBakus = [
            [
                'nama_bahan' => 'Kulit Sapi Grade A',
                'satuan' => 'Lembar',
                'stok' => 45.00,
                'harga_satuan' => 150000.00,
            ],
            [
                'nama_bahan' => 'Kulit Sapi Grade B',
                'satuan' => 'Lembar',
                'stok' => 60.00,
                'harga_satuan' => 100000.00,
            ],
            [
                'nama_bahan' => 'Kulit Domba',
                'satuan' => 'Lembar',
                'stok' => 30.00,
                'harga_satuan' => 120000.00,
            ],
            [
                'nama_bahan' => 'Lem Kulit',
                'satuan' => 'Kg',
                'stok' => 18.50,
                'harga_satuan' => 45000.00,
            ],
            [
                'nama_bahan' => 'Benang Jahit Kulit',
                'satuan' => 'Gulung',
                'stok' => 35.00,
                'harga_satuan' => 25000.00,
            ],
            [
                'nama_bahan' => 'Resleting YKK',
                'satuan' => 'Pcs',
                'stok' => 80.00,
                'harga_satuan' => 8000.00,
            ],
            [
                'nama_bahan' => 'Kancing Snap',
                'satuan' => 'Pcs',
                'stok' => 150.00,
                'harga_satuan' => 3000.00,
            ],
            [
                'nama_bahan' => 'Pewarna Kulit',
                'satuan' => 'Liter',
                'stok' => 12.00,
                'harga_satuan' => 65000.00,
            ],
            [
                'nama_bahan' => 'Sol Sepatu Karet',
                'satuan' => 'Pasang',
                'stok' => 40.00,
                'harga_satuan' => 35000.00,
            ],
            [
                'nama_bahan' => 'Kain Pelapis Dalam',
                'satuan' => 'Meter',
                'stok' => 25.00,
                'harga_satuan' => 28000.00,
            ],
        ];

        foreach ($bahanBakus as $bahan) {
            BahanBaku::create($bahan);
        }
    }
}
