@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Bahan Baku
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Bahan Baku</h5>
            <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Bahan
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($bahanBakus as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $b->nama_bahan }}</strong></td>
                        <td>{{ $b->satuan }}</td>
                        <td>{{ $b->stok }}</td>
                        <td>Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('bahan-baku.edit', $b->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('bahan-baku.destroy', $b->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item" onclick="return confirm('Yakin hapus bahan baku?')">
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
                            Tidak ada data bahan baku
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection