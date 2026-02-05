<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Gaji;
use App\Models\BahanBaku;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter bulan/tahun dari request, default bulan ini
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // PENDAPATAN (dari penjualan)
        $pendapatan = Penjualan::whereMonth('tanggal_penjualan', $bulan)
            ->whereYear('tanggal_penjualan', $tahun)
            ->sum('total');

        // BEBAN POKOK PENJUALAN (dari pembelian bahan baku)
        $bebanPokok = Pembelian::whereMonth('tanggal_pembelian', $bulan)
            ->whereYear('tanggal_pembelian', $tahun)
            ->sum('total');

        // BEBAN GAJI
        $bebanGaji = Gaji::where(function($query) use ($bulan, $tahun) {
                $query->whereMonth('periode_awal', $bulan)
                      ->whereYear('periode_awal', $tahun);
            })
            ->orWhere(function($query) use ($bulan, $tahun) {
                $query->whereMonth('periode_akhir', $bulan)
                      ->whereYear('periode_akhir', $tahun);
            })
            ->where('status', 'dibayar')
            ->sum('jumlah_gaji');

        // TOTAL BEBAN
        $totalBeban = $bebanPokok + $bebanGaji;

        // LABA KOTOR
        $labaKotor = $pendapatan - $bebanPokok;

        // LABA BERSIH
        $labaBersih = $pendapatan - $totalBeban;

        // DATA CHART - Penjualan vs Pembelian 6 bulan terakhir
        $chartData = $this->getChartData();

        // STATISTIK RINGKAS
        $totalPenjualanBulanIni = Penjualan::whereMonth('tanggal_penjualan', $bulan)
            ->whereYear('tanggal_penjualan', $tahun)
            ->count();

        $totalPembelianBulanIni = Pembelian::whereMonth('tanggal_pembelian', $bulan)
            ->whereYear('tanggal_pembelian', $tahun)
            ->count();

        $stokBahanBaku = BahanBaku::count();
        $stokProduk = Produk::sum('stok');

        return view('dashboard', compact(
            'pendapatan',
            'bebanPokok',
            'bebanGaji',
            'totalBeban',
            'labaKotor',
            'labaBersih',
            'bulan',
            'tahun',
            'chartData',
            'totalPenjualanBulanIni',
            'totalPembelianBulanIni',
            'stokBahanBaku',
            'stokProduk'
        ));
    }

    private function getChartData()
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulan = $date->month;
            $tahun = $date->year;

            $penjualan = Penjualan::whereMonth('tanggal_penjualan', $bulan)
                ->whereYear('tanggal_penjualan', $tahun)
                ->sum('total');

            $pembelian = Pembelian::whereMonth('tanggal_pembelian', $bulan)
                ->whereYear('tanggal_pembelian', $tahun)
                ->sum('total');

            $data[] = [
                'bulan' => $date->format('M Y'),
                'penjualan' => (float) $penjualan,
                'pembelian' => (float) $pembelian,
            ];
        }

        return $data;
    }
}