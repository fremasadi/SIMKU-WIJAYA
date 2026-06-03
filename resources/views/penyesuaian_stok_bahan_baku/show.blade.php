@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold mb-3">Detail Penyesuaian Stok</h4>

    <div class="card p-4">
        <table class="table table-borderless">
            <tr>
                <th style="width: 220px;">Tanggal</th>
                <td>{{ $penyesuaianStok->tanggal->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>Bahan Baku</th>
                <td>{{ $penyesuaianStok->bahanBaku->nama_bahan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis</th>
                <td>{{ ucfirst($penyesuaianStok->jenis) }}</td>
            </tr>
            <tr>
                <th>Jumlah Dikurangi</th>
                <td>
                    {{ number_format($penyesuaianStok->jumlah, 2, ',', '.') }}
                    {{ $penyesuaianStok->bahanBaku->satuan ?? '' }}
                </td>
            </tr>
            <tr>
                <th>User</th>
                <td>{{ $penyesuaianStok->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>{{ $penyesuaianStok->catatan ?: '-' }}</td>
            </tr>
        </table>

        <div class="mt-3">
            <a href="{{ route('penyesuaian-stok-bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

</div>
@endsection
