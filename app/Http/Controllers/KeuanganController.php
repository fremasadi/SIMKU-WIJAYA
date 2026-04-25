<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'detailPenjualans',
            'detailPembelians',
            'detailGajis'
        );
    }
}
