<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    /**
     * Menampilkan daftar bahan baku
     */
    public function index()
    {
        $bahanBakus = BahanBaku::latest()->get();
        return view('bahan_baku.index', compact('bahanBakus'));
    }

    /**
     * Form tambah bahan baku
     */
    public function create()
    {
        return view('bahan_baku.create');
    }

    /**
     * Simpan bahan baku
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan'    => 'required|string|max:255',
            'satuan'        => 'required|string|max:50',
            'stok'          => 'required|numeric|min:0',
            'harga_satuan'  => 'required|numeric|min:0',
        ]);

        BahanBaku::create($request->all());

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan');
    }

    /**
     * Form edit bahan baku
     */
    public function edit($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        return view('bahan_baku.edit', compact('bahanBaku'));
    }

    /**
     * Update bahan baku
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan'    => 'required|string|max:255',
            'satuan'        => 'required|string|max:50',
            'stok'          => 'required|numeric|min:0',
            'harga_satuan'  => 'required|numeric|min:0',
        ]);

        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->update($request->all());

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil diperbarui');
    }

    /**
     * Hapus bahan baku
     */
    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->delete();

        return redirect()
            ->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil dihapus');
    }
}