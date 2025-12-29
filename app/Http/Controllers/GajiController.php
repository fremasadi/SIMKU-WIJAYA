<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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

        $gajis = Gaji::with('karyawan')
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

    /**
     * Auto-generate gaji mingguan
     */
    public function generate()
    {
        $today = Carbon::today();
        $karyawans = Karyawan::all();

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

            // Hitung ketidakhadiran di periode ini
            $tidakHadir = Presensi::where('karyawan_id', $karyawan->id)
                                ->whereBetween('tanggal', [$periode_awal, $periode_akhir])
                                ->where('status_hadir', 'Tidak Hadir')
                                ->count();


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

        }


        return redirect()->back()->with('success', 'Gaji otomatis berhasil digenerate.');
    }
}