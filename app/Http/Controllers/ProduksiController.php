<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\DetailProduksi;
use App\Models\Produk;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProduksiController extends Controller
{
    /**
     * List produksi
     */
    public function index()
    {
        $produksis = Produksi::with('produk')->latest()->get();
        return view('produksi.index', compact('produksis'));
    }

    /**
     * Form tambah produksi
     */
    public function create()
    {
        $produks = Produk::orderBy('nama_produk')->get();
        $bahanBakus = BahanBaku::where('stok', '>', 0)->orderBy('nama_bahan')->get();
        return view('produksi.create', compact('produks', 'bahanBakus'));
    }

    /**
     * Simpan produksi + detail + update stok produk & bahan baku
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tanggal_produksi' => 'required|date',
            'jumlah_produksi' => 'required|numeric|min:0.01',
            'bahan_baku_id' => 'required|array|min:1',
            'bahan_baku_id.*' => 'required|exists:bahan_bakus,id',
            'jumlah_bahan' => 'required|array|min:1',
            'jumlah_bahan.*' => 'required|numeric|min:0.01',
        ]);

        $bahanIds = collect($request->bahan_baku_id)->filter()->unique()->values();
        $pemakaianPerBahan = [];
        $barisPerBahan = [];

        foreach ($request->bahan_baku_id as $index => $bahanId) {
            $jumlahBahan = (float) ($request->jumlah_bahan[$index] ?? 0);

            $pemakaianPerBahan[$bahanId] = ($pemakaianPerBahan[$bahanId] ?? 0) + $jumlahBahan;
            $barisPerBahan[$bahanId][] = $index;
        }

        DB::transaction(function () use ($request, $bahanIds, $pemakaianPerBahan, $barisPerBahan) {
            $stokBahan = BahanBaku::whereIn('id', $bahanIds)->lockForUpdate()->get()->keyBy('id');
            $errors = [];

            foreach ($pemakaianPerBahan as $bahanId => $totalPemakaian) {
                $bahan = $stokBahan->get($bahanId);

                if (!$bahan) {
                    continue;
                }

                if ($totalPemakaian > (float) $bahan->stok) {
                    $message = "Pemakaian {$bahan->nama_bahan} melebihi stok tersedia ({$bahan->stok} {$bahan->satuan}).";

                    foreach ($barisPerBahan[$bahanId] as $rowIndex) {
                        $errors["jumlah_bahan.$rowIndex"] = $message;
                    }
                }
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
            }

            // 1️⃣ Simpan header produksi
            $produksi = Produksi::create([
                'produk_id' => $request->produk_id,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jumlah_produksi' => $request->jumlah_produksi,
            ]);

            // 2️⃣ Simpan detail produksi & kurangi stok bahan baku
            foreach ($request->bahan_baku_id as $i => $bahanId) {
                $jumlahBahan = $request->jumlah_bahan[$i];

                DetailProduksi::create([
                    'produksi_id' => $produksi->id,
                    'bahan_baku_id' => $bahanId,
                    'jumlah_bahan' => $jumlahBahan,
                ]);

                // Kurangi stok bahan baku
                $bahan = $stokBahan->get($bahanId);
                $bahan->decrement('stok', $jumlahBahan);
            }

            // 3️⃣ Tambahkan stok produk jadi
            $produk = Produk::findOrFail($request->produk_id);
            $produk->increment('stok', $request->jumlah_produksi);
        });

        return redirect()->route('produksi.index')->with('success', 'Produksi berhasil disimpan');
    }

    /**
     * Detail produksi
     */
    public function show($id)
    {
        $produksi = Produksi::with(['produk', 'detailProduksis.bahanBaku'])->findOrFail($id);
        return view('produksi.show', compact('produksi'));
    }

    /**
     * Hapus produksi (rollback stok)
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $produksi = Produksi::with('detailProduksis')->findOrFail($id);

            // rollback stok bahan baku
            foreach ($produksi->detailProduksis as $detail) {
                $detail->bahanBaku->increment('stok', $detail->jumlah_bahan);
            }

            // rollback stok produk
            $produksi->produk->decrement('stok', $produksi->jumlah_produksi);

            $produksi->delete();
        });

        return redirect()->route('produksi.index')->with('success', 'Produksi berhasil dihapus');
    }
}
