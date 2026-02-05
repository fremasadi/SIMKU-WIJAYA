<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        $karyawans = Karyawan::all();
        $startDate = Carbon::parse('2025-10-01');
        $endDate = Carbon::parse('2026-01-31');

        // Pattern ketidakhadiran per karyawan (tanggal-tanggal tidak hadir)
        $tidakHadir = [
            1 => ['2025-10-14', '2025-11-21', '2025-12-25', '2026-01-01'],
            2 => ['2025-10-07', '2025-10-28', '2025-11-18', '2025-12-25', '2025-12-26', '2026-01-01'],
            3 => ['2025-10-21', '2025-11-04', '2025-12-09', '2025-12-25', '2026-01-01', '2026-01-20'],
            4 => ['2025-10-15', '2025-11-25', '2025-12-25', '2025-12-31', '2026-01-01'],
            5 => ['2025-10-08', '2025-10-29', '2025-11-12', '2025-12-25', '2026-01-01', '2026-01-14'],
            6 => ['2025-10-22', '2025-11-06', '2025-11-28', '2025-12-25', '2026-01-01'],
            7 => ['2025-10-03', '2025-11-14', '2025-12-19', '2025-12-25', '2025-12-26', '2026-01-01'],
            8 => ['2025-10-16', '2025-10-30', '2025-11-20', '2025-12-25', '2026-01-01', '2026-01-27'],
        ];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            // Skip hari Minggu (weekend)
            if ($date->isSunday()) {
                continue;
            }

            foreach ($karyawans as $karyawan) {
                $tanggalStr = $date->format('Y-m-d');
                $absenList = $tidakHadir[$karyawan->id] ?? [];

                $status = in_array($tanggalStr, $absenList) ? 'Tidak Hadir' : 'Hadir';

                Presensi::create([
                    'tanggal' => $tanggalStr,
                    'status_hadir' => $status,
                    'karyawan_id' => $karyawan->id,
                ]);
            }
        }
    }
}
