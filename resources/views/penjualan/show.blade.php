@extends('layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Penjualan /</span> Detail Penjualan
    </h4>

    {{-- CARD INFO PENJUALAN --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Transaksi</h5>

            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Kode Penjualan</th>
                            <td>: {{ $penjualan->kode_penjualan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ $penjualan->tanggal_penjualan->format('d-m-Y') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Kasir</th>
                            <td>: {{ $penjualan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Transaksi</th>
                            <td>
                                : <strong>
                                    Rp {{ number_format($penjualan->total, 2, ',', '.') }}
                                  </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- CARD DETAIL ITEM --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Detail Item Penjualan</h5>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan->detailPenjualans as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->nama_produk }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->harga, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Tidak ada item penjualan
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($penjualan->detailPenjualans->count())
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th>
                            Rp {{ number_format($penjualan->total, 2, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>

</div>

@endsection