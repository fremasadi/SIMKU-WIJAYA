@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Gaji Karyawan
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(auth()->user()->role == 'admin')
    <div class="mb-3">
        <a href="{{ route('gaji.generate') }}" class="btn btn-primary">
            Generate Gaji Otomatis
        </a>
    </div>
    @endif
    <form method="GET" class="mb-3 row g-2 align-items-center">
    <div class="col-auto">
        <input type="date" name="start" class="form-control" value="{{ $start->format('Y-m-d') }}">
    </div>
    <div class="col-auto">
        <input type="date" name="end" class="form-control" value="{{ $end->format('Y-m-d') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Jumlah Gaji</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gajis as $g)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $g->karyawan->nama }}</td>
                        <td>{{ $g->periode_awal->format('d-m-Y') }}</td>
                        <td>{{ $g->periode_akhir->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($g->jumlah_gaji,0,',','.') }}</td>
                        <td>{{ $g->tanggal_bayar->format('d-m-Y') }}</td>
                        <td>
                            @if($g->status == 'Belum Dibayar')
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#confirmPayModal{{ $g->id }}">
                                    Tandai Dibayar
                                </button>
                            @else
                                <span class="badge bg-success">Dibayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Belum ada data gaji
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($gajis as $g)
        @if($g->status == 'Belum Dibayar')
            @php
                $totalPotongan = $g->potongans->sum('jumlah_potongan');
            @endphp

            <div class="modal fade" id="confirmPayModal{{ $g->id }}" tabindex="-1" aria-labelledby="confirmPayModalLabel{{ $g->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmPayModalLabel{{ $g->id }}">Konfirmasi Pembayaran Gaji</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="text-muted small">Karyawan</div>
                                    <div class="fw-semibold">{{ $g->karyawan->nama }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Periode</div>
                                    <div class="fw-semibold">{{ $g->periode_awal->format('d-m-Y') }} s/d {{ $g->periode_akhir->format('d-m-Y') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Total Potongan</div>
                                    <div class="fw-semibold">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Gaji Diterima</div>
                                    <div class="fw-semibold">Rp {{ number_format($g->jumlah_gaji, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <h6 class="mb-2">Rincian Potongan</h6>
                            @if($g->potongans->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Keterangan</th>
                                                <th class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($g->potongans as $potongan)
                                            <tr>
                                                <td>{{ $potongan->tanggal->format('d-m-Y') }}</td>
                                                <td>{{ $potongan->jenis }}</td>
                                                <td>{{ $potongan->keterangan ?? '-' }}</td>
                                                <td class="text-end">Rp {{ number_format($potongan->jumlah_potongan, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    Tidak ada rincian potongan untuk gaji ini.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('gaji.bayar', $g->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning">Ya, Tandai Dibayar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</div>
@endsection
