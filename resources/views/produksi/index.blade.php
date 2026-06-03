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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Produksi</h5>
            @if(auth()->user()->role != 'owner')
            <a href="{{ route('produksi.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Produksi
            </a>
            @endif
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Tanggal Produksi</th>
                        <th>Jumlah Produksi/Pcs</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksis as $p)
                    <tr>
                        <td>{{ $produksis->firstItem() + $loop->index }}</td>
                        <td>{{ $p->produk->nama_produk }}</td>
                        <td>{{ $p->tanggal_produksi->format('d-m-Y') }}</td>
                        <td>{{ $p->jumlah_produksi }}</td>
                        <td>
                            @php
                                $statusClass = [
                                    'proses' => 'bg-label-warning',
                                    'selesai' => 'bg-label-success',
                                    'gagal' => 'bg-label-danger',
                                    'batal' => 'bg-label-secondary',
                                ][$p->status] ?? 'bg-label-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $p->status_label }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('produksi.show', $p->id) }}">
                                        <i class="bx bx-show-alt me-1"></i> Detail
                                    </a>
                                    @if(auth()->user()->role != 'owner' && $p->status === \App\Models\Produksi::STATUS_PROSES)
                                        <form action="{{ route('produksi.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button class="dropdown-item" onclick="return confirm('Selesaikan produksi ini? Stok produk akan bertambah.')">
                                                <i class="bx bx-check-circle me-1"></i> Selesai
                                            </button>
                                        </form>
                                        <form action="{{ route('produksi.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="gagal">
                                            <button class="dropdown-item" onclick="return confirm('Tandai produksi gagal? Stok bahan baku tidak akan kembali.')">
                                                <i class="bx bx-x-circle me-1"></i> Gagal
                                            </button>
                                        </form>
                                        <form action="{{ route('produksi.updateStatus', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="batal">
                                            <button class="dropdown-item" onclick="return confirm('Batalkan produksi ini? Stok bahan baku akan dikembalikan.')">
                                                <i class="bx bx-undo me-1"></i> Batal
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada data produksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $produksis->links() }}
    </div>

</div>
@endsection
