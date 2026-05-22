@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Presensi</h4>
            <p class="mb-0 text-muted">
                Rekap {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('presensi.rekap') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="start">Dari Tanggal</label>
                    <input type="date" id="start" name="start" class="form-control" value="{{ $start->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="end">Sampai Tanggal</label>
                    <input type="date" id="end" name="end" class="form-control" value="{{ $end->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="karyawan_id">Karyawan</label>
                    <select id="karyawan_id" name="karyawan_id" class="form-select">
                        <option value="">Semua Karyawan</option>
                        @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}" {{ (string) $karyawanId === (string) $karyawan->id ? 'selected' : '' }}>
                                {{ $karyawan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    <a href="{{ route('presensi.rekap') }}" class="btn btn-label-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Total Presensi</p>
                            <h4 class="mb-1">{{ number_format($totalPresensi, 0, ',', '.') }}</h4>
                            <small class="text-muted">Data periode terpilih</small>
                        </div>
                        <span class="badge bg-label-info rounded p-2">
                            <i class="bx bx-calendar bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Hadir</p>
                            <h4 class="mb-1 text-success">{{ number_format($totalHadir, 0, ',', '.') }}</h4>
                            <small class="text-muted">Total hadir</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-check-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Tidak Hadir</p>
                            <h4 class="mb-1 text-danger">{{ number_format($totalTidakHadir, 0, ',', '.') }}</h4>
                            <small class="text-muted">Dengan catatan alasan</small>
                        </div>
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bx-x-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Persentase Hadir</p>
                            <h4 class="mb-1 text-primary">{{ number_format($persentaseHadir, 2, ',', '.') }}%</h4>
                            <small class="text-muted">Hadir / total presensi</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-pie-chart-alt-2 bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rekap Per Karyawan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 8%;">#</th>
                                <th>Nama Karyawan</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Hadir</th>
                                <th class="text-end">Tidak Hadir</th>
                                <th class="text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapKaryawans as $karyawan)
                                @php
                                    $persentase = $karyawan->total_presensi > 0
                                        ? ($karyawan->total_hadir / $karyawan->total_presensi) * 100
                                        : 0;
                                @endphp
                                <tr>
                                    <td>{{ $rekapKaryawans->firstItem() + $loop->index }}</td>
                                    <td class="fw-semibold">{{ $karyawan->nama }}</td>
                                    <td class="text-end">{{ number_format($karyawan->total_presensi, 0, ',', '.') }}</td>
                                    <td class="text-end text-success">{{ number_format($karyawan->total_hadir, 0, ',', '.') }}</td>
                                    <td class="text-end text-danger">{{ number_format($karyawan->total_tidak_hadir, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($persentase, 2, ',', '.') }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data rekap presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $rekapKaryawans->links() }}
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Catatan Tidak Hadir</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 8%;">#</th>
                                <th style="width: 16%;">Tanggal</th>
                                <th style="width: 24%;">Karyawan</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detailTidakHadir as $presensi)
                                <tr>
                                    <td>{{ $detailTidakHadir->firstItem() + $loop->index }}</td>
                                    <td>{{ $presensi->tanggal->format('d/m/Y') }}</td>
                                    <td class="fw-semibold">{{ $presensi->karyawan->nama }}</td>
                                    <td>{{ $presensi->alasan_tidak_hadir ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Tidak ada catatan tidak hadir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $detailTidakHadir->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
