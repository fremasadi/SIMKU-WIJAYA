<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk
     */
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    /**
     * Form tambah produk
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan'      => 'required|string|max:20',
        ]);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'satuan'      => $request->satuan,
            'stok'        => 0, // stok awal
        ]);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Form edit produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan'      => 'required|string|max:20',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->only('nama_produk', 'satuan'));

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}