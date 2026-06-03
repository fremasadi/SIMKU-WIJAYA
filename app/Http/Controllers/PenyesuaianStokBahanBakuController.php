<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PenyesuaianStokBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenyesuaianStokBahanBakuController extends Controller
{
    /**
     * Menampilkan riwayat penyesuaian stok bahan baku.
     */
    public function index()
    {
        $penyesuaianStoks = PenyesuaianStokBahanBaku::with(['bahanBaku', 'user'])
            ->latest('tanggal')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penyesuaian_stok_bahan_baku.index', compact('penyesuaianStoks'));
    }

    /**
     * Form tambah penyesuaian stok.
     */
    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('penyesuaian_stok_bahan_baku.create', compact('bahanBakus'));
    }

    /**
     * Simpan penyesuaian stok dan kurangi stok bahan baku.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jenis' => 'required|in:busuk,rusak,hilang,kadaluarsa,koreksi,lainnya',
            'jumlah' => 'required|numeric|min:0.01',
            'catatan' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $bahanBaku = BahanBaku::whereKey($validated['bahan_baku_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ((float) $validated['jumlah'] > (float) $bahanBaku->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah penyesuaian melebihi stok tersedia.',
                ]);
            }

            PenyesuaianStokBahanBaku::create([
                'bahan_baku_id' => $bahanBaku->id,
                'user_id' => Auth::id(),
                'tanggal' => $validated['tanggal'],
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'catatan' => $validated['catatan'],
            ]);

            $bahanBaku->decrement('stok', $validated['jumlah']);
        });

        return redirect()
            ->route('penyesuaian-stok-bahan-baku.index')
            ->with('success', 'Penyesuaian stok berhasil disimpan');
    }

    /**
     * Detail penyesuaian stok.
     */
    public function show($id)
    {
        $penyesuaianStok = PenyesuaianStokBahanBaku::with(['bahanBaku', 'user'])->findOrFail($id);

        return view('penyesuaian_stok_bahan_baku.show', compact('penyesuaianStok'));
    }
}
