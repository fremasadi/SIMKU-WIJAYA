<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\DetailPembelian;
use App\Models\Pembelian;
use Illuminate\Database\Seeder;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $bahanBakus = BahanBaku::all()->keyBy('nama_bahan');

        $pembelians = [
            // Pembelian 1 - Oktober 2025
            [
                'tanggal_pembelian' => '2025-10-05',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade A', 'jumlah' => 50, 'harga' => 150000],
                    ['bahan' => 'Kulit Sapi Grade B', 'jumlah' => 40, 'harga' => 100000],
                    ['bahan' => 'Lem Kulit', 'jumlah' => 10, 'harga' => 45000],
                    ['bahan' => 'Benang Jahit Kulit', 'jumlah' => 20, 'harga' => 25000],
                    ['bahan' => 'Sol Sepatu Karet', 'jumlah' => 30, 'harga' => 35000],
                ],
            ],
            // Pembelian 2 - Oktober 2025
            [
                'tanggal_pembelian' => '2025-10-20',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Kulit Domba', 'jumlah' => 25, 'harga' => 120000],
                    ['bahan' => 'Resleting YKK', 'jumlah' => 50, 'harga' => 8000],
                    ['bahan' => 'Kancing Snap', 'jumlah' => 100, 'harga' => 3000],
                    ['bahan' => 'Pewarna Kulit', 'jumlah' => 8, 'harga' => 65000],
                ],
            ],
            // Pembelian 3 - November 2025
            [
                'tanggal_pembelian' => '2025-11-08',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade A', 'jumlah' => 35, 'harga' => 150000],
                    ['bahan' => 'Kain Pelapis Dalam', 'jumlah' => 30, 'harga' => 28000],
                    ['bahan' => 'Benang Jahit Kulit', 'jumlah' => 15, 'harga' => 25000],
                    ['bahan' => 'Sol Sepatu Karet', 'jumlah' => 20, 'harga' => 35000],
                ],
            ],
            // Pembelian 4 - November 2025
            [
                'tanggal_pembelian' => '2025-11-25',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade B', 'jumlah' => 30, 'harga' => 100000],
                    ['bahan' => 'Kulit Domba', 'jumlah' => 20, 'harga' => 120000],
                    ['bahan' => 'Lem Kulit', 'jumlah' => 8, 'harga' => 45000],
                    ['bahan' => 'Kancing Snap', 'jumlah' => 80, 'harga' => 3000],
                ],
            ],
            // Pembelian 5 - Desember 2025
            [
                'tanggal_pembelian' => '2025-12-03',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade A', 'jumlah' => 40, 'harga' => 155000],
                    ['bahan' => 'Resleting YKK', 'jumlah' => 60, 'harga' => 8500],
                    ['bahan' => 'Pewarna Kulit', 'jumlah' => 10, 'harga' => 65000],
                    ['bahan' => 'Kain Pelapis Dalam', 'jumlah' => 20, 'harga' => 28000],
                ],
            ],
            // Pembelian 6 - Desember 2025
            [
                'tanggal_pembelian' => '2025-12-18',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade B', 'jumlah' => 25, 'harga' => 102000],
                    ['bahan' => 'Sol Sepatu Karet', 'jumlah' => 25, 'harga' => 36000],
                    ['bahan' => 'Benang Jahit Kulit', 'jumlah' => 20, 'harga' => 25000],
                    ['bahan' => 'Lem Kulit', 'jumlah' => 12, 'harga' => 46000],
                ],
            ],
            // Pembelian 7 - Januari 2026
            [
                'tanggal_pembelian' => '2026-01-07',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade A', 'jumlah' => 30, 'harga' => 155000],
                    ['bahan' => 'Kulit Domba', 'jumlah' => 15, 'harga' => 122000],
                    ['bahan' => 'Kancing Snap', 'jumlah' => 100, 'harga' => 3000],
                    ['bahan' => 'Resleting YKK', 'jumlah' => 40, 'harga' => 8500],
                ],
            ],
            // Pembelian 8 - Januari 2026
            [
                'tanggal_pembelian' => '2026-01-22',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Kulit Sapi Grade B', 'jumlah' => 35, 'harga' => 103000],
                    ['bahan' => 'Sol Sepatu Karet', 'jumlah' => 30, 'harga' => 36000],
                    ['bahan' => 'Kain Pelapis Dalam', 'jumlah' => 25, 'harga' => 29000],
                    ['bahan' => 'Pewarna Kulit', 'jumlah' => 6, 'harga' => 67000],
                ],
            ],
        ];

        foreach ($pembelians as $data) {
            $total = 0;
            $details = [];

            foreach ($data['details'] as $detail) {
                $subtotal = $detail['jumlah'] * $detail['harga'];
                $total += $subtotal;
                $details[] = [
                    'bahan_baku_id' => $bahanBakus[$detail['bahan']]->id,
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'subtotal' => $subtotal,
                ];
            }

            $pembelian = Pembelian::create([
                'tanggal_pembelian' => $data['tanggal_pembelian'],
                'total' => $total,
                'user_id' => $data['user_id'],
            ]);

            foreach ($details as $detail) {
                DetailPembelian::create(array_merge($detail, [
                    'pembelian_id' => $pembelian->id,
                ]));
            }
        }
    }
}
