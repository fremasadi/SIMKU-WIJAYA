<?php

namespace Database\Seeders;

use App\Models\Gaji;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GajiSeeder extends Seeder
{
    private const GAJI_HARIAN = 50000;

    public function run(): void
    {
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
                $workingDays = $this->countWorkingDays($periodStart, $periodEnd);

                foreach ($karyawans as $karyawan) {
                    Gaji::create([
                        'karyawan_id' => $karyawan->id,
                        'periode_awal' => $periodStart,
                        'periode_akhir' => $periodEnd,
                        'jumlah_gaji' => self::GAJI_HARIAN * $workingDays,
                        'tanggal_bayar' => $periodEnd,
                        'status' => $month['status'],
                    ]);
                }
            }
        }
    }

    private function countWorkingDays(Carbon $start, Carbon $end): int
    {
        $days = 0;

        for ($date = $start->copy()->startOfDay(); $date->lte($end); $date->addDay()) {
            if (!$date->isSunday()) {
                $days++;
            }
        }

        return $days;
    }
}
