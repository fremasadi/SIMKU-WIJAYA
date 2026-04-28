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
                <a href="{{ route('keuangan.exportPdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
                    rel="noopener" class="btn btn-sm btn-danger">
                    Preview PDF
                </a>
            </form>
        </div>

        <!-- <div class="row mb-4">
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
                        </div> -->

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Laporan Keuangan</h5>
                            <small class="text-muted">
                                Periode: {{ $periodeMulai->format('d/m/Y') }} - {{ $periodeSelesai->format('d/m/Y') }}
                            </small>
                        </div>
                        <span class="badge bg-label-{{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                            Kondisi {{ $kondisiKeuangan }}
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 14%;">Tanggal</th>
                                    <th style="width: 36%;">Keterangan</th>
                                    <th class="text-end" style="width: 16%;">Pemasukan</th>
                                    <th class="text-end" style="width: 16%;">Pengeluaran</th>
                                    <th class="text-end" style="width: 18%;">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiKeuangan as $item)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                                        <td>{{ $item['keterangan'] }}</td>
                                        <td class="text-end">
                                            {{ $item['pemasukan'] > 0 ? 'Rp ' . number_format($item['pemasukan'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $item['pengeluaran'] > 0 ? 'Rp ' . number_format($item['pengeluaran'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td
                                            class="text-end fw-semibold {{ $item['saldo'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($item['saldo'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi pada periode
                                            ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-uppercase">Total</th>
                                    <th class="text-end text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</th>
                                    <th class="text-end text-danger">Rp {{ number_format($totalBeban, 0, ',', '.') }}</th>
                                    <th class="text-end {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection