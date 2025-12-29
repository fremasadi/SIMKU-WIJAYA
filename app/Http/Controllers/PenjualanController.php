<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Menampilkan daftar penjualan
     */
    public function index()
    {
        $penjualans = Penjualan::with('user')->latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Menampilkan form tambah penjualan
     */
    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        return view('penjualan.create', compact('produks'));
    }

    /**
     * Simpan penjualan beserta detailnya
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'produk_id.*' => 'required|exists:produks,id',
            'jumlah.*' => 'required|numeric|min:0.01',
            'harga.*' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;

            // hitung total penjualan
            foreach ($request->jumlah as $i => $jumlah) {
                $total += $jumlah * $request->harga[$i];
            }

            // simpan header penjualan
            $penjualan = Penjualan::create([
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            // simpan detail penjualan & kurangi stok produk
            foreach ($request->produk_id as $i => $produkId) {
                $jumlah = $request->jumlah[$i];
                $harga = $request->harga[$i];

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'nama_produk' => Produk::findOrFail($produkId)->nama_produk,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $jumlah * $harga,
                ]);

                // kurangi stok produk
                $produk = Produk::findOrFail($produkId);
                $produk->decrement('stok', $jumlah);
            }
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Penjualan berhasil disimpan');
    }

    /**
     * Menampilkan detail penjualan
     */
    public function show($id)
    {
        $penjualan = Penjualan::with('detailPenjualans')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Hapus penjualan & rollback stok
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $penjualan = Penjualan::with('detailPenjualans')->findOrFail($id);

            // rollback stok produk
            foreach ($penjualan->detailPenjualans as $detail) {
                $produk = Produk::where('nama_produk', $detail->nama_produk)->first();
                if ($produk) {
                    $produk->increment('stok', $detail->jumlah);
                }
            }

            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }
}