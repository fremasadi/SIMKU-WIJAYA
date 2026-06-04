<?php

namespace Database\Seeders;

use App\Models\Gaji;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GajiSeeder extends Seeder
{
    public function run(): void
    {
        // Gaji bulanan dasar berdasarkan jabatan, dibagi proporsional per minggu.
        $gajiPerJabatan = [
            'Karyawan Jemur' => 30000,
            'Karyawan Produksi' => 40000,
        ];

        $karyawans = Karyawan::all();
        $months = [
            ['year' => 2025, 'month' => 10, 'status' => 'Dibayar'],
            ['year' => 2025, 'month' => 11, 'status' => 'Dibayar'],
            ['year' => 2025, 'month' => 12, 'status' => 'Dibayar'],
            ['year' => 2026, 'month' => 1, 'status' => 'Belum Dibayar'],
        ];

        foreach ($months as $month) {
            $monthStart = Carbon::create($month['year'], $month['month'], 1);
            $monthEnd = $monthStart->copy()->endOfMonth();

            for ($periodStart = $monthStart->copy(); $periodStart->lte($monthEnd); $periodStart->addDays(7)) {
                $periodEnd = $periodStart->copy()->addDays(6)->min($monthEnd);
                $periodDays = $periodStart->diffInDays($periodEnd) + 1;

                foreach ($karyawans as $karyawan) {
                    Gaji::create([
                        'karyawan_id' => $karyawan->id,
                        'periode_awal' => $periodStart,
                        'periode_akhir' => $periodEnd,
                        'jumlah_gaji' => ($gajiPerJabatan[$karyawan->jabatan] / $monthEnd->day) * $periodDays,
                        'tanggal_bayar' => $periodEnd,
                        'status' => $month['status'],
                    ]);
                }
            }
        }
    }
}
