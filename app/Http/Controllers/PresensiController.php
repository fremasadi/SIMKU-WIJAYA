<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Menampilkan daftar presensi
     */
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $karyawanId = $request->karyawan_id ?? null;

        // Auto-generate presensi hari ini
        $existing = Presensi::where('tanggal', $date)->count();
        if ($existing == 0) {
            $karyawans = Karyawan::all();
            foreach ($karyawans as $k) {
                Presensi::create([
                    'tanggal' => $date,
                    'karyawan_id' => $k->id,
                    'status_hadir' => 'Tidak Hadir',
                ]);
            }
        }

        $query = Presensi::with('karyawan')->where('tanggal', $date);
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }
        $presensis = $query->get();

        $karyawans = Karyawan::all();
        return view('presensi.index', compact('presensis', 'karyawans', 'date'));
    }

    /**
     * Menampilkan form tambah presensi
     */
    public function create()
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        return view('presensi.create', compact('karyawans'));
    }

    /**
     * Simpan presensi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|exists:karyawans,id',
            'status_hadir' => 'required|in:Hadir,Tidak Hadir',
        ]);

        Presensi::create($request->all());

        return redirect()
            ->route('presensi.index')
            ->with('success', 'Presensi berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit presensi
     */
    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        $karyawans = Karyawan::orderBy('nama')->get();
        return view('presensi.edit', compact('presensi', 'karyawans'));
    }

    /**
     * Update presensi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|exists:karyawans,id',
            'status_hadir' => 'required|in:Hadir,Tidak Hadir',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->update($request->all());

        return redirect()
            ->route('presensi.index')
            ->with('success', 'Presensi berhasil diperbarui');
    }

    /**
     * Hapus presensi
     */
    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        return redirect()
            ->route('presensi.index')
            ->with('success', 'Presensi berhasil dihapus');
    }

    /**
 * Update status presensi dari checklist
 */
public function updateStatus(Request $request)
{
    $statuses = $request->input('status_hadir', []);

    foreach ($statuses as $id => $status) {
        $presensi = Presensi::find($id);
        if ($presensi) {
            $presensi->status_hadir = $status;
            $presensi->save();
        }
    }

    return redirect()
        ->back()
        ->with('success', 'Presensi berhasil diperbarui');
}
}