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

        // DATA CHART - Tren produk terlaris per minggu
        $topSellingProductsTrend = $this->getTopSellingProductsWeeklyTrend($bulan, $tahun);

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
            'topSellingProductsTrend',
            'totalPenjualanBulanIni',
            'totalPembelianBulanIni',
            'stokBahanBaku',
            'stokProduk'
        ));
    }

    private function getTopSellingProductsWeeklyTrend($bulan, $tahun)
    {
        $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();
        $weeks = [];
        $weekStart = $startOfMonth->copy();

        while ($weekStart->lte($endOfMonth)) {
            $weekEnd = $weekStart->copy()->addDays(6)->min($endOfMonth);
            $weekNumber = count($weeks) + 1;

            $weeks[] = [
                'label' => 'Minggu ' . $weekNumber . ' (' . $weekStart->format('d') . '-' . $weekEnd->format('d M') . ')',
                'start' => $weekStart->copy(),
                'end' => $weekEnd->copy(),
            ];

            $weekStart = $weekEnd->copy()->addDay()->startOfDay();
        }

        $topProducts = DB::table('detail_penjualans')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->select('detail_penjualans.nama_produk', DB::raw('SUM(detail_penjualans.jumlah) as total_jumlah'))
            ->whereMonth('penjualans.tanggal_penjualan', $bulan)
            ->whereYear('penjualans.tanggal_penjualan', $tahun)
            ->groupBy('detail_penjualans.nama_produk')
            ->orderByDesc('total_jumlah')
            ->limit(4)
            ->get();

        $productNames = $topProducts->pluck('nama_produk');

        $sales = DB::table('detail_penjualans')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->select(
                'detail_penjualans.nama_produk',
                'penjualans.tanggal_penjualan',
                DB::raw('SUM(detail_penjualans.jumlah) as total_jumlah')
            )
            ->whereIn('detail_penjualans.nama_produk', $productNames)
            ->whereBetween('penjualans.tanggal_penjualan', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->groupBy('detail_penjualans.nama_produk', 'penjualans.tanggal_penjualan')
            ->get();

        $colors = [
            ['border' => 'rgb(54, 162, 235)', 'background' => 'rgba(54, 162, 235, 0.18)'],
            ['border' => 'rgb(75, 192, 128)', 'background' => 'rgba(75, 192, 128, 0.18)'],
            ['border' => 'rgb(255, 159, 64)', 'background' => 'rgba(255, 159, 64, 0.16)'],
            ['border' => 'rgb(153, 102, 255)', 'background' => 'rgba(153, 102, 255, 0.16)'],
        ];

        $datasets = $topProducts->values()->map(function ($product, $index) use ($weeks, $sales, $colors) {
            $data = collect($weeks)->map(function ($week) use ($sales, $product) {
                return (float) $sales
                    ->filter(function ($item) use ($week, $product) {
                        $tanggal = Carbon::parse($item->tanggal_penjualan);

                        return $item->nama_produk === $product->nama_produk
                            && $tanggal->betweenIncluded($week['start'], $week['end']);
                    })
                    ->sum('total_jumlah');
            })->values();

            return [
                'label' => $product->nama_produk,
                'data' => $data,
                'borderColor' => $colors[$index]['border'],
                'backgroundColor' => $colors[$index]['background'],
            ];
        });

        return [
            'labels' => collect($weeks)->pluck('label'),
            'datasets' => $datasets,
        ];
    }
}
