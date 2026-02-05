<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // Harga jual produk
        $hargaJual = [
            'Sepatu Kulit Pria' => 450000,
            'Sepatu Kulit Wanita' => 425000,
            'Tas Kulit Selempang' => 350000,
            'Tas Kulit Ransel' => 550000,
            'Dompet Kulit Pria' => 175000,
            'Dompet Kulit Wanita' => 165000,
            'Ikat Pinggang Kulit' => 125000,
            'Jaket Kulit' => 850000,
        ];

        $penjualanList = [
            // Penjualan 1 - Oktober
            [
                'tanggal_penjualan' => '2025-10-18',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Pria', 'jumlah' => 5],
                    ['nama_produk' => 'Dompet Kulit Pria', 'jumlah' => 8],
                ],
            ],
            // Penjualan 2 - Oktober
            [
                'tanggal_penjualan' => '2025-10-28',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Tas Kulit Selempang', 'jumlah' => 3],
                    ['nama_produk' => 'Ikat Pinggang Kulit', 'jumlah' => 5],
                ],
            ],
            // Penjualan 3 - November
            [
                'tanggal_penjualan' => '2025-11-10',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Pria', 'jumlah' => 8],
                    ['nama_produk' => 'Dompet Kulit Pria', 'jumlah' => 10],
                ],
            ],
            // Penjualan 4 - November
            [
                'tanggal_penjualan' => '2025-11-18',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Wanita', 'jumlah' => 5],
                    ['nama_produk' => 'Tas Kulit Selempang', 'jumlah' => 4],
                ],
            ],
            // Penjualan 5 - November
            [
                'tanggal_penjualan' => '2025-11-28',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Dompet Kulit Wanita', 'jumlah' => 8],
                    ['nama_produk' => 'Ikat Pinggang Kulit', 'jumlah' => 6],
                ],
            ],
            // Penjualan 6 - Desember
            [
                'tanggal_penjualan' => '2025-12-05',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Pria', 'jumlah' => 10],
                    ['nama_produk' => 'Tas Kulit Ransel', 'jumlah' => 3],
                ],
            ],
            // Penjualan 7 - Desember
            [
                'tanggal_penjualan' => '2025-12-15',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Jaket Kulit', 'jumlah' => 3],
                    ['nama_produk' => 'Dompet Kulit Pria', 'jumlah' => 12],
                ],
            ],
            // Penjualan 8 - Desember
            [
                'tanggal_penjualan' => '2025-12-22',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Wanita', 'jumlah' => 6],
                    ['nama_produk' => 'Dompet Kulit Wanita', 'jumlah' => 5],
                ],
            ],
            // Penjualan 9 - Desember
            [
                'tanggal_penjualan' => '2025-12-28',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Ikat Pinggang Kulit', 'jumlah' => 8],
                    ['nama_produk' => 'Tas Kulit Selempang', 'jumlah' => 5],
                ],
            ],
            // Penjualan 10 - Januari
            [
                'tanggal_penjualan' => '2026-01-08',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Pria', 'jumlah' => 12],
                    ['nama_produk' => 'Jaket Kulit', 'jumlah' => 2],
                ],
            ],
            // Penjualan 11 - Januari
            [
                'tanggal_penjualan' => '2026-01-18',
                'user_id' => 1,
                'items' => [
                    ['nama_produk' => 'Dompet Kulit Pria', 'jumlah' => 15],
                    ['nama_produk' => 'Ikat Pinggang Kulit', 'jumlah' => 10],
                ],
            ],
            // Penjualan 12 - Januari
            [
                'tanggal_penjualan' => '2026-01-28',
                'user_id' => 2,
                'items' => [
                    ['nama_produk' => 'Sepatu Kulit Wanita', 'jumlah' => 8],
                    ['nama_produk' => 'Tas Kulit Ransel', 'jumlah' => 2],
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
