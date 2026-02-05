<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // Harga jual produk kerupuk per Kg
        $hargaJual = [
            'Kerupuk Udang Premium' => 45000,
            'Kerupuk Ikan Tenggiri' => 38000,
            'Kerupuk Bawang' => 28000,
            'Kerupuk Kulit Sapi' => 50000,
            'Kerupuk Singkong' => 22000,
            'Kerupuk Gendar' => 25000,
            'Kerupuk Terasi' => 35000,
            'Kerupuk Palembang' => 40000,
        ];

        $penjualanList = [
            // Penjualan 1 - Oktober
            [
                'tanggal_penjualan' => '2025-10-05',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 50],
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 80],
                ],
            ],
            // Penjualan 2 - Oktober
            [
                'tanggal_penjualan' => '2025-10-12',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Ikan Tenggiri', 'jumlah' => 60],
                    ['nama_produk' => 'Kerupuk Kulit Sapi', 'jumlah' => 40],
                ],
            ],
            // Penjualan 3 - Oktober
            [
                'tanggal_penjualan' => '2025-10-20',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Singkong', 'jumlah' => 100],
                    ['nama_produk' => 'Kerupuk Gendar', 'jumlah' => 80],
                    ['nama_produk' => 'Kerupuk Terasi', 'jumlah' => 50],
                ],
            ],
            // Penjualan 4 - Oktober
            [
                'tanggal_penjualan' => '2025-10-28',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 60],
                    ['nama_produk' => 'Kerupuk Palembang', 'jumlah' => 55],
                ],
            ],
            // Penjualan 5 - November
            [
                'tanggal_penjualan' => '2025-11-05',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 90],
                    ['nama_produk' => 'Kerupuk Ikan Tenggiri', 'jumlah' => 70],
                ],
            ],
            // Penjualan 6 - November
            [
                'tanggal_penjualan' => '2025-11-12',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Kulit Sapi', 'jumlah' => 50],
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 55],
                    ['nama_produk' => 'Kerupuk Gendar', 'jumlah' => 60],
                ],
            ],
            // Penjualan 7 - November
            [
                'tanggal_penjualan' => '2025-11-20',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Singkong', 'jumlah' => 120],
                    ['nama_produk' => 'Kerupuk Terasi', 'jumlah' => 65],
                ],
            ],
            // Penjualan 8 - November
            [
                'tanggal_penjualan' => '2025-11-28',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Palembang', 'jumlah' => 70],
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 85],
                ],
            ],
            // Penjualan 9 - Desember
            [
                'tanggal_penjualan' => '2025-12-05',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 80],
                    ['nama_produk' => 'Kerupuk Ikan Tenggiri', 'jumlah' => 75],
                ],
            ],
            // Penjualan 10 - Desember
            [
                'tanggal_penjualan' => '2025-12-12',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Kulit Sapi', 'jumlah' => 60],
                    ['nama_produk' => 'Kerupuk Gendar', 'jumlah' => 90],
                    ['nama_produk' => 'Kerupuk Terasi', 'jumlah' => 55],
                ],
            ],
            // Penjualan 11 - Desember
            [
                'tanggal_penjualan' => '2025-12-18',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Singkong', 'jumlah' => 130],
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 100],
                ],
            ],
            // Penjualan 12 - Desember (menjelang akhir tahun - volume besar)
            [
                'tanggal_penjualan' => '2025-12-24',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 100],
                    ['nama_produk' => 'Kerupuk Palembang', 'jumlah' => 80],
                    ['nama_produk' => 'Kerupuk Ikan Tenggiri', 'jumlah' => 65],
                ],
            ],
            // Penjualan 13 - Desember
            [
                'tanggal_penjualan' => '2025-12-30',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 95],
                    ['nama_produk' => 'Kerupuk Kulit Sapi', 'jumlah' => 45],
                ],
            ],
            // Penjualan 14 - Januari
            [
                'tanggal_penjualan' => '2026-01-08',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 70],
                    ['nama_produk' => 'Kerupuk Singkong', 'jumlah' => 110],
                    ['nama_produk' => 'Kerupuk Terasi', 'jumlah' => 60],
                ],
            ],
            // Penjualan 15 - Januari
            [
                'tanggal_penjualan' => '2026-01-15',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Ikan Tenggiri', 'jumlah' => 80],
                    ['nama_produk' => 'Kerupuk Gendar', 'jumlah' => 75],
                    ['nama_produk' => 'Kerupuk Palembang', 'jumlah' => 60],
                ],
            ],
            // Penjualan 16 - Januari
            [
                'tanggal_penjualan' => '2026-01-22',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Kerupuk Bawang', 'jumlah' => 110],
                    ['nama_produk' => 'Kerupuk Kulit Sapi', 'jumlah' => 55],
                ],
            ],
            // Penjualan 17 - Januari
            [
                'tanggal_penjualan' => '2026-01-28',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Kerupuk Udang Premium', 'jumlah' => 75],
                    ['nama_produk' => 'Kerupuk Singkong', 'jumlah' => 90],
                    ['nama_produk' => 'Kerupuk Terasi', 'jumlah' => 70],
                ],
            ],
        ];

        foreach ($penjualanList as $data) {
            $total = 0;
            $items = [];

            foreach ($data['items'] as $item) {
                $harga = $hargaJual[$item['nama_produk']];
                $subtotal = $item['jumlah'] * $harga;
                $total += $subtotal;
                $items[] = [
                    'nama_produk' => $item['nama_produk'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            $penjualan = Penjualan::create([
                'tanggal_penjualan' => $data['tanggal_penjualan'],
                'total' => $total,
                'user_id' => $data['user_id'],
            ]);

            foreach ($items as $item) {
                DetailPenjualan::create(array_merge($item, [
                    'penjualan_id' => $penjualan->id,
                ]));
            }
        }
    }
}
