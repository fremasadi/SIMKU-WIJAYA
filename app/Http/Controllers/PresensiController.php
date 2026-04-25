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

        $this->ensurePresensiForDate($date);

        $query = Presensi::with('karyawan')->where('tanggal', $date);
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }
        $presensis = $query->get();

        $karyawans = Karyawan::all();
        return view('presensi.index', compact('presensis', 'karyawans', 'date'));
    }

    /**
     * Pastikan setiap karyawan yang sudah masuk punya data presensi di tanggal ini.
     */
    private function ensurePresensiForDate(string $date): void
    {
        $karyawans = Karyawan::whereDate('tanggal_masuk', '<=', $date)->get();

        foreach ($karyawans as $karyawan) {
            Presensi::firstOrCreate(
                [
                    'tanggal' => $date,
                    'karyawan_id' => $karyawan->id,
                ],
                [
                    'status_hadir' => 'Tidak Hadir',
                ]
            );
        }
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
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $presensiIds = $request->input('presensi_ids', []);
        $statuses = $request->input('status_hadir', []);

        foreach ($presensiIds as $id) {
            $presensi = Presensi::find($id);
            if ($presensi) {
                $presensi->status_hadir = $statuses[$id] ?? 'Tidak Hadir';
                $presensi->save();
            }
        }

        return redirect()
            ->route('presensi.index', array_filter([
                'date' => $request->input('date'),
                'karyawan_id' => $request->input('karyawan_id'),
            ]))
            ->with('success', 'Presensi berhasil diperbarui');
    }
}
