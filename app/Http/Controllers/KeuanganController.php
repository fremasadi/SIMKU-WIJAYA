<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('keuangan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $fileName = 'laporan-keuangan-' . $data['bulan'] . '-' . $data['tahun'] . '.pdf';

        $pdf = Pdf::loadView('keuangan.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }

    private function getReportData(Request $request): array
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        $penjualanQuery = Penjualan::with('user')
            ->whereMonth('tanggal_penjualan', $bulan)
            ->whereYear('tanggal_penjualan', $tahun)
            ->orderBy('tanggal_penjualan')
            ->orderBy('id');

        $pembelianQuery = Pembelian::with('user')
            ->whereMonth('tanggal_pembelian', $bulan)
            ->whereYear('tanggal_pembelian', $tahun)
            ->orderBy('tanggal_pembelian')
            ->orderBy('id');

        $gajiQuery = Gaji::with('karyawan')
            ->where(function ($query) use ($bulan, $tahun) {
                $query->where(function ($periode) use ($bulan, $tahun) {
                    $periode->whereMonth('periode_awal', $bulan)
                        ->whereYear('periode_awal', $tahun);
                })
                ->orWhere(function ($periode) use ($bulan, $tahun) {
                    $periode->whereMonth('periode_akhir', $bulan)
                        ->whereYear('periode_akhir', $tahun);
                });
            })
            ->where('status', 'Dibayar')
            ->orderBy('tanggal_bayar')
            ->orderBy('id');

        $detailPenjualans = $penjualanQuery->get();
        $detailPembelians = $pembelianQuery->get();
        $detailGajis = $gajiQuery->get();

        $pendapatan = $detailPenjualans->sum('total');
        $bebanPokok = $detailPembelians->sum('total');
        $bebanGaji = $detailGajis->sum('jumlah_gaji');

        $totalBeban = $bebanPokok + $bebanGaji;
        $labaKotor = $pendapatan - $bebanPokok;
        $labaBersih = $pendapatan - $totalBeban;
        $marginBersih = $pendapatan > 0 ? ($labaBersih / $pendapatan) * 100 : 0;

        $totalTransaksiPenjualan = $detailPenjualans->count();
        $totalTransaksiPembelian = $detailPembelians->count();
        $totalGajiDibayar = $detailGajis->count();

        $transaksiKeuangan = collect()
            ->merge($detailPenjualans->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_penjualan,
                    'keterangan' => $item->kode_penjualan ? 'Penjualan ' . $item->kode_penjualan : 'Penjualan Produk',
                    'pemasukan' => (float) $item->total,
                    'pengeluaran' => 0,
                    'urutan' => 1,
                    'id' => $item->id,
                ];
            }))
            ->merge($detailPembelians->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_pembelian,
                    'keterangan' => 'Pembelian Bahan Baku #' . $item->id,
                    'pemasukan' => 0,
                    'pengeluaran' => (float) $item->total,
                    'urutan' => 2,
                    'id' => $item->id,
                ];
            }))
            ->merge($detailGajis->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_bayar ?? $item->periode_akhir,
                    'keterangan' => 'Pembayaran Gaji - ' . ($item->karyawan->nama ?? 'Karyawan'),
                    'pemasukan' => 0,
                    'pengeluaran' => (float) $item->jumlah_gaji,
                    'urutan' => 3,
                    'id' => $item->id,
                ];
            }))
            ->sortBy([
                ['tanggal', 'asc'],
                ['urutan', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        $saldoBerjalan = 0;
        $transaksiKeuangan = $transaksiKeuangan->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += $item['pemasukan'] - $item['pengeluaran'];
            $item['saldo'] = $saldoBerjalan;

            return $item;
        });

        $periodeMulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $periodeSelesai = ($bulan === (int) now()->month && $tahun === (int) now()->year)
            ? now()
            : Carbon::create($tahun, $bulan, 1)->endOfMonth();
        $kondisiKeuangan = $labaBersih >= 0 ? 'POSITIF' : 'NEGATIF';

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return compact(
            'bulan',
            'tahun',
            'namaBulan',
            'pendapatan',
            'bebanPokok',
            'bebanGaji',
            'totalBeban',
            'labaKotor',
            'labaBersih',
            'marginBersih',
            'totalTransaksiPenjualan',
            'totalTransaksiPembelian',
            'totalGajiDibayar',
            'transaksiKeuangan',
            'periodeMulai',
            'periodeSelesai',
            'kondisiKeuangan',
            'detailPenjualans',
            'detailPembelians',
            'detailGajis'
        );
    }
}
