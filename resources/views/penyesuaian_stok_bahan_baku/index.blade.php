@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Penyesuaian Stok
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Penyesuaian Stok Bahan Baku</h5>
            @if(auth()->user()->role != 'owner')
            <a href="{{ route('penyesuaian-stok-bahan-baku.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Penyesuaian
            </a>
            @endif
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Bahan Baku</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyesuaianStoks as $p)
                    <tr>
                        <td>{{ $penyesuaianStoks->firstItem() + $loop->index }}</td>
                        <td>{{ $p->tanggal->format('d-m-Y') }}</td>
                        <td><strong>{{ $p->bahanBaku->nama_bahan ?? '-' }}</strong></td>
                        <td>{{ ucfirst($p->jenis) }}</td>
                        <td>{{ number_format($p->jumlah, 2, ',', '.') }} {{ $p->bahanBaku->satuan ?? '' }}</td>
                        <td>{{ $p->user->name ?? '-' }}</td>
                        <td>
                            <a class="btn btn-sm btn-info" href="{{ route('penyesuaian-stok-bahan-baku.show', $p->id) }}">
                                <i class="bx bx-show-alt me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada riwayat penyesuaian stok
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $penyesuaianStoks->links() }}
    </div>

</div>
@endsection
