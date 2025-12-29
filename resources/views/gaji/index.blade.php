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

    <div class="mb-3">
        <a href="{{ route('gaji.generate') }}" class="btn btn-primary">
            Generate Gaji Otomatis
        </a>
    </div>
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
                                <form action="{{ route('gaji.bayar', $g->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-warning">Tandai Dibayar</button>
                                </form>
                            @else
                                <span class="badge bg-success">Dibayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada data gaji
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection