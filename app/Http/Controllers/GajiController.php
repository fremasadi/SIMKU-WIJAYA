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
    const GAJI_MINGGUAN = 300000; // gaji per 2 minggu
    const POTONGAN = 50000;       // potongan per ketidakhadiran

    /**
     * Menampilkan daftar gaji
     */
    public function index(Request $request)
    {
        $today = \Carbon\Carbon::today();
        $default_start = $today->copy()->subDays(13); // 2 minggu terakhir
        $default_end = $today;

        // Ambil filter dari request jika ada
        $start = $request->start ? \Carbon\Carbon::parse($request->start) : $default_start;
        $end = $request->end ? \Carbon\Carbon::parse($request->end) : $default_end;

        $gajis = Gaji::with([
                'karyawan',
                'potongans' => fn ($query) => $query->orderBy('tanggal'),
            ])
            ->whereBetween('periode_awal', [$start, $end])
            ->orWhereBetween('periode_akhir', [$start, $end])
            ->latest()
            ->get();

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
     * Auto-generate gaji mingguan
     */
    public function generate()
    {
        $karyawans = Karyawan::all();

        DB::transaction(function () use ($karyawans) {
            foreach ($karyawans as $karyawan) {

                // Cek gaji terakhir
                $lastGaji = Gaji::where('karyawan_id', $karyawan->id)
                                ->orderByDesc('periode_akhir')
                                ->first();

                if ($lastGaji) {
                    $periode_awal = Carbon::parse($lastGaji->periode_akhir)->addDay();
                } else {
                    $periode_awal = Carbon::parse($karyawan->tanggal_masuk);
                }

                $periode_akhir = (clone $periode_awal)->addDays(13); // 2 minggu

                // Ambil ketidakhadiran di periode ini untuk hitung dan rincian potongan
                $presensiTidakHadir = Presensi::where('karyawan_id', $karyawan->id)
                                            ->whereBetween('tanggal', [$periode_awal, $periode_akhir])
                                            ->where('status_hadir', 'Tidak Hadir')
                                            ->get();

                $tidakHadir = $presensiTidakHadir->count();
                $jumlahGaji = self::GAJI_MINGGUAN - ($tidakHadir * self::POTONGAN);

                // Simpan gaji
                $gaji = Gaji::create([
                    'karyawan_id' => $karyawan->id,
                    'periode_awal' => $periode_awal,
                    'periode_akhir' => $periode_akhir,
                    'jumlah_gaji' => max($jumlahGaji, 0),
                    'tanggal_bayar' => $periode_akhir,
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
            }
        });


        return redirect()->back()->with('success', 'Gaji otomatis berhasil digenerate.');
    }
}
