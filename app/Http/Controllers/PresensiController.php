<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Menampilkan daftar presensi
     */
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $selectedDate = Carbon::parse($date);
        $today = Carbon::today();
        $isSunday = $selectedDate->isSunday();
        $isPastDate = $selectedDate->lt($today);
        $isFutureDate = $selectedDate->gt($today);
        $canManagePresensi = auth()->user()->role === 'admin'
            && $selectedDate->isSameDay($today)
            && !$isSunday;
        $karyawanId = $request->karyawan_id ?? null;

        if ($selectedDate->isSameDay($today) && !$isSunday) {
            $this->ensurePresensiForDate($date);
        }

        $query = Presensi::with('karyawan')->where('tanggal', $date);
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }
        $presensis = $query->paginate(10)->withQueryString();

        $karyawans = Karyawan::all();
        return view('presensi.index', compact(
            'presensis',
            'karyawans',
            'date',
            'canManagePresensi',
            'isSunday',
            'isPastDate',
            'isFutureDate'
        ));
    }

    /**
     * Rekap presensi untuk admin dan owner.
     */
    public function rekap(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $start = $request->filled('start')
            ? Carbon::parse($request->start)->startOfDay()
            : Carbon::now()->startOfMonth();
        $end = $request->filled('end')
            ? Carbon::parse($request->end)->endOfDay()
            : Carbon::now()->endOfMonth();
        $karyawanId = $request->input('karyawan_id');

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $basePresensiQuery = Presensi::query()
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->when($karyawanId, fn ($query) => $query->where('karyawan_id', $karyawanId));

        $totalPresensi = (clone $basePresensiQuery)->count();
        $totalHadir = (clone $basePresensiQuery)->where('status_hadir', 'Hadir')->count();
        $totalTidakHadir = (clone $basePresensiQuery)->where('status_hadir', 'Tidak Hadir')->count();
        $persentaseHadir = $totalPresensi > 0 ? ($totalHadir / $totalPresensi) * 100 : 0;

        $rekapKaryawans = Karyawan::query()
            ->when($karyawanId, fn ($query) => $query->where('id', $karyawanId))
            ->withCount([
                'presensis as total_presensi' => fn ($query) => $query
                    ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]),
                'presensis as total_hadir' => fn ($query) => $query
                    ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
                    ->where('status_hadir', 'Hadir'),
                'presensis as total_tidak_hadir' => fn ($query) => $query
                    ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
                    ->where('status_hadir', 'Tidak Hadir'),
            ])
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $detailTidakHadir = Presensi::with('karyawan')
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->where('status_hadir', 'Tidak Hadir')
            ->when($karyawanId, fn ($query) => $query->where('karyawan_id', $karyawanId))
            ->orderByDesc('tanggal')
            ->orderBy('karyawan_id')
            ->paginate(10, ['*'], 'alasan_page')
            ->withQueryString();

        $karyawans = Karyawan::orderBy('nama')->get();

        return view('presensi.rekap', compact(
            'start',
            'end',
            'karyawanId',
            'karyawans',
            'rekapKaryawans',
            'detailTidakHadir',
            'totalPresensi',
            'totalHadir',
            'totalTidakHadir',
            'persentaseHadir'
        ));
    }

    /**
     * Pastikan setiap karyawan yang sudah masuk punya data presensi di tanggal ini.
     */
    private function ensurePresensiForDate(string $date): void
    {
        $selectedDate = Carbon::parse($date);

        if (!$selectedDate->isSameDay(Carbon::today()) || $selectedDate->isSunday()) {
            return;
        }

        $karyawans = Karyawan::whereDate('tanggal_masuk', '<=', $date)->get();

        foreach ($karyawans as $karyawan) {
            Presensi::firstOrCreate(
                [
                    'tanggal' => $date,
                    'karyawan_id' => $karyawan->id,
                ],
                [
                    'status_hadir' => 'Tidak Hadir',
                    'alasan_tidak_hadir' => null,
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
        $tanggal = Carbon::parse($request->tanggal);

        if (!$tanggal->isSameDay(Carbon::today()) || $tanggal->isSunday()) {
            return back()->with('error', 'Presensi hanya bisa diisi untuk hari ini dan bukan hari Minggu.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|exists:karyawans,id',
            'status_hadir' => 'required|in:Hadir,Tidak Hadir',
            'alasan_tidak_hadir' => 'nullable|required_if:status_hadir,Tidak Hadir|string|max:500',
        ]);

        Presensi::create([
            'tanggal' => $request->tanggal,
            'karyawan_id' => $request->karyawan_id,
            'status_hadir' => $request->status_hadir,
            'alasan_tidak_hadir' => $request->status_hadir === 'Tidak Hadir'
                ? $request->alasan_tidak_hadir
                : null,
        ]);

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
        $tanggal = Carbon::parse($request->tanggal);

        if (!$tanggal->isSameDay(Carbon::today()) || $tanggal->isSunday()) {
            return back()->with('error', 'Presensi yang sudah lewat atau hari Minggu tidak bisa diperbaiki.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|exists:karyawans,id',
            'status_hadir' => 'required|in:Hadir,Tidak Hadir',
            'alasan_tidak_hadir' => 'nullable|required_if:status_hadir,Tidak Hadir|string|max:500',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'tanggal' => $request->tanggal,
            'karyawan_id' => $request->karyawan_id,
            'status_hadir' => $request->status_hadir,
            'alasan_tidak_hadir' => $request->status_hadir === 'Tidak Hadir'
                ? $request->alasan_tidak_hadir
                : null,
        ]);

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

        $date = $request->input('date', now()->toDateString());
        $selectedDate = Carbon::parse($date);

        if (!$selectedDate->isSameDay(Carbon::today()) || $selectedDate->isSunday()) {
            return redirect()
                ->route('presensi.index', array_filter([
                    'date' => $date,
                    'karyawan_id' => $request->input('karyawan_id'),
                ]))
                ->with('error', 'Presensi hanya bisa diisi atau diperbaiki untuk hari ini dan bukan hari Minggu.');
        }

        $presensiIds = $request->input('presensi_ids', []);
        $statuses = $request->input('status_hadir', []);
        $alasans = $request->input('alasan_tidak_hadir', []);

        foreach ($presensiIds as $id) {
            if (!isset($statuses[$id]) && empty(trim($alasans[$id] ?? ''))) {
                return redirect()
                    ->route('presensi.index', array_filter([
                        'date' => $date,
                        'karyawan_id' => $request->input('karyawan_id'),
                        'edit' => 1,
                    ]))
                    ->withInput()
                    ->with('error', 'Alasan wajib diisi untuk karyawan yang tidak hadir.');
            }
        }

        foreach ($presensiIds as $id) {
            $presensi = Presensi::whereDate('tanggal', $date)->find($id);
            if ($presensi) {
                $statusHadir = isset($statuses[$id]) ? 'Hadir' : 'Tidak Hadir';

                $presensi->status_hadir = $statusHadir;
                $presensi->alasan_tidak_hadir = $statusHadir === 'Tidak Hadir'
                    ? trim($alasans[$id] ?? '')
                    : null;
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
