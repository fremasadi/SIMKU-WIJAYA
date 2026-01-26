@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Produksi /</span> Detail Produksi
    </h4>

    {{-- CARD INFO PRODUKSI --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Produksi</h5>

            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Nama Produk</th>
                            <td>: {{ $produksi->produk->nama_produk }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Produksi</th>
                            <td>: {{ $produksi->tanggal_produksi->format('d-m-Y') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Jumlah Produksi</th>
                            <td>: {{ $produksi->jumlah_produksi }} pcs</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD RIWAYAT BAHAN BAKU --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Riwayat Penggunaan Bahan Baku</h5>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Jumlah Dipakai</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produksi->detailProduksis as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->bahanBaku->nama_bahan }}</td>
                            <td>{{ $detail->jumlah_bahan }}</td>
                            <td>{{ $detail->bahanBaku->satuan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada data penggunaan bahan baku
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- BUTTON --}}
    <div class="mt-4">
        <a href="{{ route('produksi.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
    </div>

</div>
@endsection