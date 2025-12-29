<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Menampilkan daftar karyawan
     */
    public function index()
    {
        $karyawans = Karyawan::latest()->get();
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Menampilkan form tambah karyawan
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Menyimpan data karyawan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_masuk' => 'required|date',
        ]);

        Karyawan::create($request->all());

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan');
    }

    /**
     * Menampilkan detail karyawan
     */
    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Menampilkan form edit karyawan
     */
    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Mengupdate data karyawan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_masuk' => 'required|date',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    /**
     * Menghapus data karyawan
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus');
    }
}