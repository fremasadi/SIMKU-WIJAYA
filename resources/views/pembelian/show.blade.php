@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pembelian /</span> Detail Pembelian
    </h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Pembelian</h5>

            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Tanggal Pembelian</th>
                            <td>: {{ $pembelian->tanggal_pembelian->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Item</th>
                            <td>: {{ $pembelian->detailPembelians->count() }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">User</th>
                            <td>: {{ $pembelian->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Pembelian</th>
                            <td>: <strong>Rp {{ number_format($pembelian->total, 2, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-bold">Detail Bahan Baku</h5>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian->detailPembelians as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->bahanBaku->nama_bahan ?? '-' }}</td>
                        <td>{{ number_format($detail->jumlah, 2, ',', '.') }}</td>
                        <td>{{ $detail->bahanBaku->satuan ?? '-' }}</td>
                        <td>Rp {{ number_format($detail->harga, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Tidak ada detail pembelian
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($pembelian->detailPembelians->count())
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">TOTAL</th>
                        <th>Rp {{ number_format($pembelian->total, 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>

</div>
@endsection
