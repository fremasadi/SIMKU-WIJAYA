@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Penjualan /</span> Daftar Penjualan
    </h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Penjualan</h5>
            @if(auth()->user()->role != 'owner')
                <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Penjualan
                </a>
            @endif
        </div>

        <div class="card-body border-bottom">
            <form action="{{ route('penjualan.index') }}" method="GET" class="row gx-3 gy-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="start_date">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="end_date">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-label-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Penjualan</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->kode_penjualan }}</td>
                            <td>{{ $p->tanggal_penjualan->format('d-m-Y') }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ number_format($p->total, 2) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('penjualan.show', $p->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Detail
                                        </a>
                                        <form action="{{ route('penjualan.destroy', $p->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" onclick="return confirm('Yakin hapus penjualan?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data penjualan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
