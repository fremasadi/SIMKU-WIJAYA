<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class GajiController extends Controller
{
    const GAJI_HARIAN = 50000;    // gaji per hari kerja
    const POTONGAN = 50000;       // potongan per ketidakhadiran

    /**
     * Menampilkan daftar gaji
     */
    public function index(Request $request)
    {
        $today = \Carbon\Carbon::today();
        $default_start = $today->copy()->startOfMonth();
        $default_end = $today->copy()->endOfMonth();

        // Ambil filter dari request jika ada
        $start = $request->start ? \Carbon\Carbon::parse($request->start) : $default_start;
        $end = $request->end ? \Carbon\Carbon::parse($request->end) : $default_end;

        $gajis = Gaji::with([
                'karyawan',
                'potongans' => fn ($query) => $query->orderBy('tanggal'),
            ])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('periode_awal', [$start, $end])
                    ->orWhereBetween('periode_akhir', [$start, $end]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('gaji.index', compact('gajis', 'start', 'end'));
    }

    public function markAsPaid(Gaji $gaji)
    {
        $gaji->update([
            'status' => 'Dibayar',
            'tanggal_bayar' => now(), // opsional, update tanggal bayar ke hari ini
        ]);

        return redirect()->back()->with('success', "Gaji untuk {$gaji->karyawan->nama} berhasil ditandai Dibayar.");
    }

    public function markSelectedAsPaid(Request $request)
    {
        $validated = $request->validate([
            'gaji_ids' => ['nullable', 'array'],
            'gaji_ids.*' => ['integer', 'exists:gajis,id'],
        ]);

        if (empty($validated['gaji_ids'])) {
            return redirect()->back()->with('success', 'Pilih minimal satu data gaji terlebih dahulu.');
        }

        $updated = Gaji::whereIn('id', $validated['gaji_ids'])
            ->where('status', 'Belum Dibayar')
            ->update([
                'status' => 'Dibayar',
                'tanggal_bayar' => now(),
            ]);

        if ($updated === 0) {
            return redirect()->back()->with('success', 'Tidak ada gaji yang perlu ditandai Dibayar.');
        }

        return redirect()->back()->with('success', "{$updated} data gaji berhasil ditandai Dibayar.");
    }

    /**
     * Auto-generate gaji untuk periode minggu berjalan.
     */
    public function generate()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        [$periodeAwal, $periodeAkhir] = $this->getCurrentWeeklyPeriod();
        $karyawans = Karyawan::whereDate('tanggal_masuk', '<=', $periodeAkhir)->get();
        $generated = 0;

        DB::transaction(function () use ($karyawans, $periodeAwal, $periodeAkhir, &$generated) {
            foreach ($karyawans as $karyawan) {
                $awalKerja = Carbon::parse($karyawan->tanggal_masuk)->startOfDay();
                $awalPeriodeKaryawan = $awalKerja->gt($periodeAwal)
                    ? $awalKerja
                    : $periodeAwal->copy();

                if ($awalPeriodeKaryawan->gt($periodeAkhir)) {
                    continue;
                }

                $sudahAda = Gaji::where('karyawan_id', $karyawan->id)
                    ->whereDate('periode_awal', $awalPeriodeKaryawan)
                    ->whereDate('periode_akhir', $periodeAkhir)
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                // Ambil ketidakhadiran di periode ini untuk hitung dan rincian potongan
                $presensiTidakHadir = Presensi::where('karyawan_id', $karyawan->id)
                    ->whereBetween('tanggal', [$awalPeriodeKaryawan, $periodeAkhir])
                    ->where('status_hadir', 'Tidak Hadir')
                    ->get();

                $tidakHadir = $presensiTidakHadir->count();
                $jumlahHariKerja = $this->countWorkingDays($awalPeriodeKaryawan, $periodeAkhir);
                $gajiPeriode = self::GAJI_HARIAN * $jumlahHariKerja;
                $jumlahGaji = $gajiPeriode - ($tidakHadir * self::POTONGAN);

                // Simpan gaji
                $gaji = Gaji::create([
                    'karyawan_id' => $karyawan->id,
                    'periode_awal' => $awalPeriodeKaryawan,
                    'periode_akhir' => $periodeAkhir,
                    'jumlah_gaji' => max($jumlahGaji, 0),
                    'tanggal_bayar' => $periodeAkhir,
                    'status' => 'Belum Dibayar',
                ]);

                foreach ($presensiTidakHadir as $presensi) {
                    $gaji->potongans()->create([
                        'presensi_id' => $presensi->id,
                        'tanggal' => $presensi->tanggal,
                        'bulan' => $presensi->tanggal->month,
                        'tahun' => $presensi->tanggal->year,
                        'jenis' => 'Tidak Hadir',
                        'keterangan' => 'Potongan karena tidak hadir',
                        'jumlah_potongan' => self::POTONGAN,
                    ]);
                }

                $generated++;
            }
        });

        if ($generated === 0) {
            return redirect()->back()->with(
                'success',
                "Gaji periode {$periodeAwal->format('d-m-Y')} s/d {$periodeAkhir->format('d-m-Y')} sudah pernah digenerate."
            );
        }

        return redirect()->back()->with(
            'success',
            "{$generated} gaji mingguan periode {$periodeAwal->format('d-m-Y')} s/d {$periodeAkhir->format('d-m-Y')} berhasil digenerate."
        );
    }

    private function getCurrentWeeklyPeriod(): array
    {
        $today = Carbon::today();
        $startDay = intdiv($today->day - 1, 7) * 7 + 1;
        $endDay = min($startDay + 6, $today->daysInMonth);

        return [
            $today->copy()->day($startDay)->startOfDay(),
            $today->copy()->day($endDay)->endOfDay(),
        ];
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
