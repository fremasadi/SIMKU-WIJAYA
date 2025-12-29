<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    /**
     * List pembelian
     */
    public function index()
    {
        $pembelians = Pembelian::with('user')
            ->latest()
            ->get();

        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Form tambah pembelian
     */
    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        return view('pembelian.create', compact('bahanBakus'));
    }

    /**
     * Simpan pembelian + detail + update stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'bahan_baku_id.*'   => 'required|exists:bahan_bakus,id',
            'jumlah.*'          => 'required|numeric|min:0.01',
            'harga.*'           => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Simpan header pembelian
            $pembelian = Pembelian::create([
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'user_id' => Auth::id(),
                'total' => 0, // nanti dihitung
            ]);

            $total = 0;

            // 2️⃣ Simpan detail pembelian
            foreach ($request->bahan_baku_id as $i => $bahanId) {

                $jumlah = $request->jumlah[$i];
                $harga  = $request->harga[$i];
                $subtotal = $jumlah * $harga;

                DetailPembelian::create([
                    'pembelian_id'  => $pembelian->id,
                    'bahan_baku_id' => $bahanId,
                    'jumlah'        => $jumlah,
                    'harga'         => $harga,
                    'subtotal'      => $subtotal,
                ]);

                // 3️⃣ Update stok bahan baku (+)
                $bahan = BahanBaku::findOrFail($bahanId);
                $bahan->increment('stok', $jumlah);
                $bahan->update(['harga_satuan' => $harga]);

                $total += $subtotal;
            }

            // 4️⃣ Update total pembelian
            $pembelian->update(['total' => $total]);
        });

        return redirect()
            ->route('pembelian.index')
            ->with('success', 'Pembelian berhasil disimpan');
    }

    /**
     * Detail pembelian
     */
    public function show($id)
    {
        $pembelian = Pembelian::with(['detailPembelians.bahanBaku', 'user'])
            ->findOrFail($id);

        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Hapus pembelian (rollback stok)
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $pembelian = Pembelian::with('detailPembelians')->findOrFail($id);

            // rollback stok
            foreach ($pembelian->detailPembelians as $detail) {
                $detail->bahanBaku->decrement('stok', $detail->jumlah);
            }

            $pembelian->delete();
        });

        return redirect()
            ->route('pembelian.index')
            ->with('success', 'Pembelian berhasil dihapus');
    }
}