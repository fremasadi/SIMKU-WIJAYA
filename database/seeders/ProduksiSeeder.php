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
            // Produksi 1 - Kerupuk Udang Premium (Oktober)
            [
                'tanggal_produksi' => '2025-10-10',
                'produk' => 'Kerupuk Udang Premium',
                'jumlah_produksi' => 100,
                'bahan' => [
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 40],
                    ['nama' => 'Udang Segar', 'jumlah' => 15],
                    ['nama' => 'Bawang Putih', 'jumlah' => 3],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Penyedap Rasa', 'jumlah' => 2],
                ],
            ],
            // Produksi 2 - Kerupuk Bawang (Oktober)
            [
                'tanggal_produksi' => '2025-10-15',
                'produk' => 'Kerupuk Bawang',
                'jumlah_produksi' => 120,
                'bahan' => [
                    ['nama' => 'Tepung Terigu', 'jumlah' => 25],
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 30],
                    ['nama' => 'Bawang Putih', 'jumlah' => 8],
                    ['nama' => 'Garam', 'jumlah' => 3],
                    ['nama' => 'Minyak Goreng', 'jumlah' => 10],
                ],
            ],
            // Produksi 3 - Kerupuk Ikan Tenggiri (Oktober)
            [
                'tanggal_produksi' => '2025-10-25',
                'produk' => 'Kerupuk Ikan Tenggiri',
                'jumlah_produksi' => 80,
                'bahan' => [
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 30],
                    ['nama' => 'Ikan Tenggiri', 'jumlah' => 12],
                    ['nama' => 'Bawang Putih', 'jumlah' => 2],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Penyedap Rasa', 'jumlah' => 1.5],
                ],
            ],
            // Produksi 4 - Kerupuk Singkong (November)
            [
                'tanggal_produksi' => '2025-11-05',
                'produk' => 'Kerupuk Singkong',
                'jumlah_produksi' => 150,
                'bahan' => [
                    ['nama' => 'Singkong', 'jumlah' => 80],
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 20],
                    ['nama' => 'Bawang Putih', 'jumlah' => 3],
                    ['nama' => 'Garam', 'jumlah' => 3],
                ],
            ],
            // Produksi 5 - Kerupuk Kulit Sapi (November)
            [
                'tanggal_produksi' => '2025-11-12',
                'produk' => 'Kerupuk Kulit Sapi',
                'jumlah_produksi' => 60,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Mentah', 'jumlah' => 25],
                    ['nama' => 'Bawang Putih', 'jumlah' => 2],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Minyak Goreng', 'jumlah' => 15],
                ],
            ],
            // Produksi 6 - Kerupuk Gendar (November)
            [
                'tanggal_produksi' => '2025-11-20',
                'produk' => 'Kerupuk Gendar',
                'jumlah_produksi' => 100,
                'bahan' => [
                    ['nama' => 'Tepung Terigu', 'jumlah' => 20],
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 25],
                    ['nama' => 'Bawang Putih', 'jumlah' => 3],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Penyedap Rasa', 'jumlah' => 1.5],
                ],
            ],
            // Produksi 7 - Kerupuk Terasi (Desember)
            [
                'tanggal_produksi' => '2025-12-01',
                'produk' => 'Kerupuk Terasi',
                'jumlah_produksi' => 90,
                'bahan' => [
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 35],
                    ['nama' => 'Bawang Putih', 'jumlah' => 3],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Penyedap Rasa', 'jumlah' => 3],
                ],
            ],
            // Produksi 8 - Kerupuk Palembang (Desember)
            [
                'tanggal_produksi' => '2025-12-10',
                'produk' => 'Kerupuk Palembang',
                'jumlah_produksi' => 100,
                'bahan' => [
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 35],
                    ['nama' => 'Ikan Tenggiri', 'jumlah' => 15],
                    ['nama' => 'Udang Segar', 'jumlah' => 8],
                    ['nama' => 'Bawang Putih', 'jumlah' => 3],
                    ['nama' => 'Garam', 'jumlah' => 2],
                ],
            ],
            // Produksi 9 - Kerupuk Udang Premium batch 2 (Desember)
            [
                'tanggal_produksi' => '2025-12-20',
                'produk' => 'Kerupuk Udang Premium',
                'jumlah_produksi' => 120,
                'bahan' => [
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 50],
                    ['nama' => 'Udang Segar', 'jumlah' => 20],
                    ['nama' => 'Bawang Putih', 'jumlah' => 4],
                    ['nama' => 'Garam', 'jumlah' => 3],
                    ['nama' => 'Penyedap Rasa', 'jumlah' => 2.5],
                ],
            ],
            // Produksi 10 - Kerupuk Bawang batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-05',
                'produk' => 'Kerupuk Bawang',
                'jumlah_produksi' => 130,
                'bahan' => [
                    ['nama' => 'Tepung Terigu', 'jumlah' => 28],
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 35],
                    ['nama' => 'Bawang Putih', 'jumlah' => 10],
                    ['nama' => 'Garam', 'jumlah' => 3],
                    ['nama' => 'Minyak Goreng', 'jumlah' => 12],
                ],
            ],
            // Produksi 11 - Kerupuk Singkong batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-15',
                'produk' => 'Kerupuk Singkong',
                'jumlah_produksi' => 160,
                'bahan' => [
                    ['nama' => 'Singkong', 'jumlah' => 90],
                    ['nama' => 'Tepung Tapioka', 'jumlah' => 25],
                    ['nama' => 'Bawang Putih', 'jumlah' => 4],
                    ['nama' => 'Garam', 'jumlah' => 3],
                ],
            ],
            // Produksi 12 - Kerupuk Kulit Sapi batch 2 (Januari)
            [
                'tanggal_produksi' => '2026-01-25',
                'produk' => 'Kerupuk Kulit Sapi',
                'jumlah_produksi' => 70,
                'bahan' => [
                    ['nama' => 'Kulit Sapi Mentah', 'jumlah' => 30],
                    ['nama' => 'Bawang Putih', 'jumlah' => 2.5],
                    ['nama' => 'Garam', 'jumlah' => 2],
                    ['nama' => 'Minyak Goreng', 'jumlah' => 18],
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
