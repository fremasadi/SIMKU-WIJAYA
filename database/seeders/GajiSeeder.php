<?php

namespace Database\Seeders;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class GajiSeeder extends Seeder
{
    public function run(): void
    {
        // Gaji bulanan berdasarkan jabatan
        $gajiPerJabatan = [
            'Karyawan Jemur' => 30000,
            'Karyawan Produksi' => 40000,

        ];

        $karyawans = Karyawan::all();

        // Gaji Oktober 2025
        foreach ($karyawans as $karyawan) {
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'periode_awal' => '2025-10-01',
                'periode_akhir' => '2025-10-31',
                'jumlah_gaji' => $gajiPerJabatan[$karyawan->jabatan],
                'tanggal_bayar' => '2025-11-03',
                'status' => 'Sudah Dibayar',
            ]);
        }

        // Gaji November 2025
        foreach ($karyawans as $karyawan) {
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'periode_awal' => '2025-11-01',
                'periode_akhir' => '2025-11-30',
                'jumlah_gaji' => $gajiPerJabatan[$karyawan->jabatan],
                'tanggal_bayar' => '2025-12-02',
                'status' => 'Sudah Dibayar',
            ]);
        }

        // Gaji Desember 2025 (dengan THR/bonus akhir tahun)
        foreach ($karyawans as $karyawan) {
            $bonus = $gajiPerJabatan[$karyawan->jabatan] * 0.5; // Bonus 50%
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'periode_awal' => '2025-12-01',
                'periode_akhir' => '2025-12-31',
                'jumlah_gaji' => $gajiPerJabatan[$karyawan->jabatan] + $bonus,
                'tanggal_bayar' => '2026-01-03',
                'status' => 'Sudah Dibayar',
            ]);
        }

        // Gaji Januari 2026 (belum dibayar)
        foreach ($karyawans as $karyawan) {
            Gaji::create([
                'karyawan_id' => $karyawan->id,
                'periode_awal' => '2026-01-01',
                'periode_akhir' => '2026-01-31',
                'jumlah_gaji' => $gajiPerJabatan[$karyawan->jabatan],
                'tanggal_bayar' => '2026-02-03',
                'status' => 'Belum Dibayar',
            ]);
        }
    }
}
