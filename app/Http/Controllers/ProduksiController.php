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
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $produksis = Produksi::with('produk')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produk', function ($produkQuery) use ($search) {
                    $produkQuery->where('nama_produk', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
                'status' => Produksi::STATUS_PROSES,
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

            // Stok produk jadi ditambahkan saat status produksi diselesaikan.
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
     * Update status produksi.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:selesai,gagal,batal',
        ]);

        DB::transaction(function () use ($request, $id) {
            $produksi = Produksi::with(['produk', 'detailProduksis.bahanBaku'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($produksi->status !== Produksi::STATUS_PROSES) {
                throw ValidationException::withMessages([
                    'status' => 'Status produksi hanya bisa diubah saat masih proses.',
                ]);
            }

            if ($request->status === Produksi::STATUS_SELESAI) {
                $produksi->produk->increment('stok', $produksi->jumlah_produksi);
            }

            if ($request->status === Produksi::STATUS_BATAL) {
                foreach ($produksi->detailProduksis as $detail) {
                    $detail->bahanBaku->increment('stok', $detail->jumlah_bahan);
                }
            }

            $produksi->update([
                'status' => $request->status,
            ]);
        });

        return redirect()->route('produksi.index')->with('success', 'Status produksi berhasil diperbarui');
    }

    /**
     * Hapus produksi (rollback stok)
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $produksi = Produksi::with(['produk', 'detailProduksis.bahanBaku'])->findOrFail($id);

            if (in_array($produksi->status, [Produksi::STATUS_PROSES, Produksi::STATUS_SELESAI], true)) {
                foreach ($produksi->detailProduksis as $detail) {
                    $detail->bahanBaku->increment('stok', $detail->jumlah_bahan);
                }
            }

            if ($produksi->status === Produksi::STATUS_SELESAI) {
                $produksi->produk->decrement('stok', $produksi->jumlah_produksi);
            }

            $produksi->delete();
        });

        return redirect()->route('produksi.index')->with('success', 'Produksi berhasil dihapus');
    }
}
