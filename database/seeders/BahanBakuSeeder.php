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
                'nama_bahan' => 'Tepung Tapioka',
                'satuan' => 'Kg',
                'stok' => 500.00,
                'harga_satuan' => 12000.00,
            ],
            [
                'nama_bahan' => 'Tepung Terigu',
                'satuan' => 'Kg',
                'stok' => 300.00,
                'harga_satuan' => 10000.00,
            ],
            [
                'nama_bahan' => 'Udang Segar',
                'satuan' => 'Kg',
                'stok' => 100.00,
                'harga_satuan' => 65000.00,
            ],
            [
                'nama_bahan' => 'Ikan Tenggiri',
                'satuan' => 'Kg',
                'stok' => 80.00,
                'harga_satuan' => 55000.00,
            ],
            [
                'nama_bahan' => 'Bawang Putih',
                'satuan' => 'Kg',
                'stok' => 50.00,
                'harga_satuan' => 35000.00,
            ],
            [
                'nama_bahan' => 'Garam',
                'satuan' => 'Kg',
                'stok' => 100.00,
                'harga_satuan' => 5000.00,
            ],
            [
                'nama_bahan' => 'Minyak Goreng',
                'satuan' => 'Liter',
                'stok' => 200.00,
                'harga_satuan' => 18000.00,
            ],
            [
                'nama_bahan' => 'Kulit Sapi Mentah',
                'satuan' => 'Kg',
                'stok' => 60.00,
                'harga_satuan' => 45000.00,
            ],
            [
                'nama_bahan' => 'Penyedap Rasa',
                'satuan' => 'Kg',
                'stok' => 30.00,
                'harga_satuan' => 25000.00,
            ],
            [
                'nama_bahan' => 'Singkong',
                'satuan' => 'Kg',
                'stok' => 300.00,
                'harga_satuan' => 8000.00,
            ],
        ];

        foreach ($bahanBakus as $bahan) {
            BahanBaku::create($bahan);
        }
    }
}
