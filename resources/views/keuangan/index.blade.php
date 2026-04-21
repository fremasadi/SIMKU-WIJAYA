@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Keuangan</h4>
            <p class="mb-0 text-muted">Periode {{ $namaBulan[$bulan] }} {{ $tahun }}</p>
        </div>

        <form method="GET" action="{{ route('keuangan.index') }}" class="d-flex gap-2">
            <select name="bulan" class="form-select form-select-sm" style="width: 130px;">
                @foreach($namaBulan as $nomorBulan => $labelBulan)
                    <option value="{{ $nomorBulan }}" {{ $bulan == $nomorBulan ? 'selected' : '' }}>
                        {{ $labelBulan }}
                    </option>
                @endforeach
            </select>
            <select name="tahun" class="form-select form-select-sm" style="width: 100px;">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('keuangan.exportPdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-sm btn-danger">
                Export PDF
            </a>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Pendapatan</p>
                            <h4 class="mb-1 text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h4>
                            <small class="text-muted">{{ $totalTransaksiPenjualan }} transaksi penjualan</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-trending-up bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Beban Pokok</p>
                            <h4 class="mb-1 text-danger">Rp {{ number_format($bebanPokok, 0, ',', '.') }}</h4>
                            <small class="text-muted">{{ $totalTransaksiPembelian }} transaksi pembelian</small>
                        </div>
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bx-shopping-bag bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Beban Gaji</p>
                            <h4 class="mb-1 text-danger">Rp {{ number_format($bebanGaji, 0, ',', '.') }}</h4>
                            <small class="text-muted">{{ $totalGajiDibayar }} gaji dibayar</small>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-wallet bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 border-{{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text mb-1">Laba Bersih</p>
                            <h4 class="mb-1 {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">Margin {{ number_format($marginBersih, 2) }}%</small>
                        </div>
                        <span class="badge bg-label-{{ $labaBersih >= 0 ? 'success' : 'danger' }} rounded p-2">
                            <i class="bx bx-dollar-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Laba Rugi</h5>
                    <small class="text-muted">{{ $namaBulan[$bulan] }} {{ $tahun }}</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold ps-0">PENDAPATAN</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Penjualan</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold ps-0 pt-3">Total Pendapatan</td>
                                    <td class="text-end fw-semibold pt-3">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
                                </tr>

                                <tr><td colspan="2"><hr class="my-3"></td></tr>

                                <tr>
                                    <td class="fw-semibold ps-0">BEBAN POKOK PENJUALAN</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Pembelian Bahan Baku</td>
                                    <td class="text-end fw-medium text-danger">(Rp {{ number_format($bebanPokok, 0, ',', '.') }})</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold ps-0 pt-3">Total Beban Pokok</td>
                                    <td class="text-end fw-semibold text-danger pt-3">(Rp {{ number_format($bebanPokok, 0, ',', '.') }})</td>
                                </tr>

                                <tr><td colspan="2"><hr class="my-3"></td></tr>

                                <tr class="table-light">
                                    <td class="fw-bold ps-0">LABA KOTOR</td>
                                    <td class="text-end fw-bold {{ $labaKotor >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr><td colspan="2"><hr class="my-3"></td></tr>

                                <tr>
                                    <td class="fw-semibold ps-0">BEBAN OPERASIONAL</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Beban Gaji</td>
                                    <td class="text-end fw-medium text-danger">(Rp {{ number_format($bebanGaji, 0, ',', '.') }})</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold ps-0 pt-3">Total Beban Operasional</td>
                                    <td class="text-end fw-semibold text-danger pt-3">(Rp {{ number_format($bebanGaji, 0, ',', '.') }})</td>
                                </tr>

                                <tr><td colspan="2"><hr class="my-3"></td></tr>

                                <tr class="table-primary">
                                    <td class="fw-bold ps-0 fs-5">LABA BERSIH</td>
                                    <td class="text-end fw-bold fs-5 {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ringkasan Beban</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Beban Pokok</span>
                        <span class="fw-semibold">Rp {{ number_format($bebanPokok, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Beban Gaji</span>
                        <span class="fw-semibold">Rp {{ number_format($bebanGaji, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total Beban</span>
                        <span class="fw-bold text-danger">Rp {{ number_format($totalBeban, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-2">Status Periode</h6>
                    @if($labaBersih >= 0)
                        <span class="badge bg-label-success">Untung</span>
                    @else
                        <span class="badge bg-label-danger">Rugi</span>
                    @endif
                    <p class="text-muted mb-0 mt-2">
                        Pendapatan dikurangi beban pokok dan beban operasional pada periode ini.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
