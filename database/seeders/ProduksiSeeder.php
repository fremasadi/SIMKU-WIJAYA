<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\DetailProduksi;
use App\Models\Produk;
use App\Models\Produksi;
use Illuminate\Database\Seeder;

class ProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $produks = Produk::all()->keyBy('nama_produk');
        $bahanBakus = BahanBaku::all()->keyBy('nama_bahan');

        $produksiList = [
            // Produksi 1 - Sepatu Kulit Pria (Oktober)
            [
                'tanggal_produksi' => '2025-10-10',
                'produk' => 'Sepatu Kulit Pria',
                'jumlah_produksi' => 20,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade A', 'jumlah' => 10],
                    ['nama' => 'Sol Sepatu Karet', 'jumlah' => 20],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 4],
                    ['nama' => 'Lem Kulit', 'jumlah' => 2],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 1.5],
                ],
            ],
            // Produksi 2 - Dompet Kulit Pria (Oktober)
            [
                'tanggal_produksi' => '2025-10-15',
                'produk' => 'Dompet Kulit Pria',
                'jumlah_produksi' => 30,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade B', 'jumlah' => 8],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 3],
                    ['nama' => 'Kancing Snap', 'jumlah' => 30],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 5],
                ],
            ],
            // Produksi 3 - Tas Kulit Selempang (Oktober)
            [
                'tanggal_produksi' => '2025-10-25',
                'produk' => 'Tas Kulit Selempang',
                'jumlah_produksi' => 15,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade A', 'jumlah' => 12],
                    ['nama' => 'Resleting YKK', 'jumlah' => 15],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 3],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 6],
                    ['nama' => 'Lem Kulit', 'jumlah' => 1.5],
                ],
            ],
            // Produksi 4 - Ikat Pinggang Kulit (November)
            [
                'tanggal_produksi' => '2025-11-05',
                'produk' => 'Ikat Pinggang Kulit',
                'jumlah_produksi' => 25,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade B', 'jumlah' => 6],
                    ['nama' => 'Kancing Snap', 'jumlah' => 25],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 1],
                ],
            ],
            // Produksi 5 - Sepatu Kulit Wanita (November)
            [
                'tanggal_produksi' => '2025-11-12',
                'produk' => 'Sepatu Kulit Wanita',
                'jumlah_produksi' => 15,
                'bahan' => [
                    ['nama' => 'Kulit Domba', 'jumlah' => 8],
                    ['nama' => 'Sol Sepatu Karet', 'jumlah' => 15],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 3],
                    ['nama' => 'Lem Kulit', 'jumlah' => 1.5],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 1],
                ],
            ],
            // Produksi 6 - Dompet Kulit Wanita (November)
            [
                'tanggal_produksi' => '2025-11-20',
                'produk' => 'Dompet Kulit Wanita',
                'jumlah_produksi' => 25,
                'bahan' => [
                    ['nama' => 'Kulit Domba', 'jumlah' => 6],
                    ['nama' => 'Resleting YKK', 'jumlah' => 25],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 2.5],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 4],
                ],
            ],
            // Produksi 7 - Tas Kulit Ransel (Desember)
            [
                'tanggal_produksi' => '2025-12-01',
                'produk' => 'Tas Kulit Ransel',
                'jumlah_produksi' => 10,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade A', 'jumlah' => 15],
                    ['nama' => 'Resleting YKK', 'jumlah' => 20],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 4],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 8],
                    ['nama' => 'Lem Kulit', 'jumlah' => 2],
                ],
            ],
            // Produksi 8 - Jaket Kulit (Desember)
            [
                'tanggal_produksi' => '2025-12-10',
                'produk' => 'Jaket Kulit',
                'jumlah_produksi' => 8,
                'bahan' => [
                    ['nama' => 'Kulit Domba', 'jumlah' => 16],
                    ['nama' => 'Resleting YKK', 'jumlah' => 8],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 4],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 10],
                    ['nama' => 'Kancing Snap', 'jumlah' => 24],
                ],
            ],
            // Produksi 9 - Sepatu Kulit Pria batch 2 (Desember)
            [
                'tanggal_produksi' => '2025-12-20',
                'produk' => 'Sepatu Kulit Pria',
                'jumlah_produksi' => 25,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade A', 'jumlah' => 13],
                    ['nama' => 'Sol Sepatu Karet', 'jumlah' => 25],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 5],
                    ['nama' => 'Lem Kulit', 'jumlah' => 2.5],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 2],
                ],
            ],
            // Produksi 10 - Ikat Pinggang batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-05',
                'produk' => 'Ikat Pinggang Kulit',
                'jumlah_produksi' => 30,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade B', 'jumlah' => 8],
                    ['nama' => 'Kancing Snap', 'jumlah' => 30],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 1.5],
                ],
            ],
            // Produksi 11 - Dompet Kulit Pria batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-15',
                'produk' => 'Dompet Kulit Pria',
                'jumlah_produksi' => 35,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Grade B', 'jumlah' => 10],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 3.5],
                    ['nama' => 'Kancing Snap', 'jumlah' => 35],
                    ['nama' => 'Kain Pelapis Dalam', 'jumlah' => 6],
                ],
            ],
            // Produksi 12 - Sepatu Kulit Wanita batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-25',
                'produk' => 'Sepatu Kulit Wanita',
                'jumlah_produksi' => 20,
                'bahan' => [
                    ['nama' => 'Kulit Domba', 'jumlah' => 10],
                    ['nama' => 'Sol Sepatu Karet', 'jumlah' => 20],
                    ['nama' => 'Benang Jahit Kulit', 'jumlah' => 4],
                    ['nama' => 'Lem Kulit', 'jumlah' => 2],
                    ['nama' => 'Pewarna Kulit', 'jumlah' => 1.5],
                ],
            ],
        ];

        foreach ($produksiList as $data) {
            $produksi = Produksi::create([
                'produk_id' => $produks[$data['produk']]->id,
                'tanggal_produksi' => $data['tanggal_produksi'],
                'jumlah_produksi' => $data['jumlah_produksi'],
            ]);

            foreach ($data['bahan'] as $bahan) {
                DetailProduksi::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $bahanBakus[$bahan['nama']]->id,
                    'jumlah_bahan' => $bahan['jumlah'],
                ]);
            }
        }
    }
}
