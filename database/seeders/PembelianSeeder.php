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
                    ['bahan' => 'Tepung Tapioka', 'jumlah' => 200, 'harga' => 12000],
                    ['bahan' => 'Udang Segar', 'jumlah' => 30, 'harga' => 65000],
                    ['bahan' => 'Bawang Putih', 'jumlah' => 15, 'harga' => 35000],
                    ['bahan' => 'Garam', 'jumlah' => 20, 'harga' => 5000],
                ],
            ],
            // Pembelian 2 - Oktober 2025
            [
                'tanggal_pembelian' => '2025-10-20',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Tepung Terigu', 'jumlah' => 100, 'harga' => 10000],
                    ['bahan' => 'Ikan Tenggiri', 'jumlah' => 25, 'harga' => 55000],
                    ['bahan' => 'Minyak Goreng', 'jumlah' => 50, 'harga' => 18000],
                    ['bahan' => 'Penyedap Rasa', 'jumlah' => 10, 'harga' => 25000],
                ],
            ],
            // Pembelian 3 - November 2025
            [
                'tanggal_pembelian' => '2025-11-08',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Tepung Tapioka', 'jumlah' => 250, 'harga' => 12000],
                    ['bahan' => 'Kulit Sapi Mentah', 'jumlah' => 30, 'harga' => 45000],
                    ['bahan' => 'Singkong', 'jumlah' => 100, 'harga' => 8000],
                    ['bahan' => 'Bawang Putih', 'jumlah' => 15, 'harga' => 35000],
                ],
            ],
            // Pembelian 4 - November 2025
            [
                'tanggal_pembelian' => '2025-11-25',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Udang Segar', 'jumlah' => 40, 'harga' => 65000],
                    ['bahan' => 'Ikan Tenggiri', 'jumlah' => 30, 'harga' => 55000],
                    ['bahan' => 'Tepung Terigu', 'jumlah' => 120, 'harga' => 10000],
                    ['bahan' => 'Garam', 'jumlah' => 25, 'harga' => 5000],
                ],
            ],
            // Pembelian 5 - Desember 2025
            [
                'tanggal_pembelian' => '2025-12-03',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Tepung Tapioka', 'jumlah' => 300, 'harga' => 12500],
                    ['bahan' => 'Minyak Goreng', 'jumlah' => 80, 'harga' => 18000],
                    ['bahan' => 'Penyedap Rasa', 'jumlah' => 15, 'harga' => 25000],
                    ['bahan' => 'Singkong', 'jumlah' => 150, 'harga' => 8000],
                ],
            ],
            // Pembelian 6 - Desember 2025
            [
                'tanggal_pembelian' => '2025-12-18',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Udang Segar', 'jumlah' => 50, 'harga' => 66000],
                    ['bahan' => 'Kulit Sapi Mentah', 'jumlah' => 35, 'harga' => 46000],
                    ['bahan' => 'Bawang Putih', 'jumlah' => 20, 'harga' => 35000],
                    ['bahan' => 'Garam', 'jumlah' => 30, 'harga' => 5000],
                ],
            ],
            // Pembelian 7 - Januari 2026
            [
                'tanggal_pembelian' => '2026-01-07',
                'user_id' => 1,
                'details' => [
                    ['bahan' => 'Tepung Tapioka', 'jumlah' => 250, 'harga' => 12500],
                    ['bahan' => 'Ikan Tenggiri', 'jumlah' => 35, 'harga' => 56000],
                    ['bahan' => 'Tepung Terigu', 'jumlah' => 100, 'harga' => 10000],
                    ['bahan' => 'Minyak Goreng', 'jumlah' => 60, 'harga' => 18500],
                ],
            ],
            // Pembelian 8 - Januari 2026
            [
                'tanggal_pembelian' => '2026-01-22',
                'user_id' => 2,
                'details' => [
                    ['bahan' => 'Udang Segar', 'jumlah' => 45, 'harga' => 66000],
                    ['bahan' => 'Singkong', 'jumlah' => 200, 'harga' => 8000],
                    ['bahan' => 'Kulit Sapi Mentah', 'jumlah' => 25, 'harga' => 46000],
                    ['bahan' => 'Penyedap Rasa', 'jumlah' => 12, 'harga' => 25000],
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
