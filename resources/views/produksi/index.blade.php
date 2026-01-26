@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Produksi /</span> Daftar Produksi
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Produksi</h5>
            <a href="{{ route('produksi.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Produksi
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Tanggal Produksi</th>
                        <th>Jumlah Produksi/Pcs</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksis as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->produk->nama_produk }}</td>
                        <td>{{ $p->tanggal_produksi->format('d-m-Y') }}</td>
                        <td>{{ $p->jumlah_produksi }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('produksi.show', $p->id) }}">
                                        <i class="bx bx-show-alt me-1"></i> Detail
                                    </a>
                                    <form action="{{ route('produksi.destroy', $p->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item" onclick="return confirm('Yakin hapus produksi?')">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Tidak ada data produksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection