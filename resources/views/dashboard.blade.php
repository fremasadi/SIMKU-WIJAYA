@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Dashboard</h4>
            <p class="mb-0 text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Filter Bulan/Tahun -->
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                <select name="bulan" class="form-select form-select-sm" style="width: 120px;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
                <select name="tahun" class="form-select form-select-sm" style="width: 100px;">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    {{-- <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text mb-1">Total Penjualan</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="mb-0 me-2">{{ $totalPenjualanBulanIni }}</h4>
                                <small class="text-muted">transaksi</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="bx bx-cart bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text mb-1">Total Pembelian</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="mb-0 me-2">{{ $totalPembelianBulanIni }}</h4>
                                <small class="text-muted">transaksi</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="bx bx-shopping-bag bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text mb-1">Stok Bahan Baku</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="mb-0 me-2">{{ $stokBahanBaku }}</h4>
                                <small class="text-muted">items</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="bx bx-package bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text mb-1">Stok Produk</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="mb-0 me-2">{{ number_format($stokProduk, 0) }}</h4>
                                <small class="text-muted">unit</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="bx bx-cube bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Laba Rugi -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Laba Rugi</h5>
                    <small class="text-muted">
                        {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
                    </small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <!-- PENDAPATAN -->
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

                                <!-- BEBAN POKOK PENJUALAN -->
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

                                <!-- LABA KOTOR -->
                                <tr class="table-light">
                                    <td class="fw-bold ps-0">LABA KOTOR</td>
                                    <td class="text-end fw-bold {{ $labaKotor >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr><td colspan="2"><hr class="my-3"></td></tr>

                                <!-- BEBAN OPERASIONAL -->
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

                                <!-- LABA BERSIH -->
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

        <!-- Summary Cards -->
        <div class="col-lg-4 mb-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Pendapatan</h6>
                        <i class="bx bx-trending-up text-success"></i>
                    </div>
                    <h3 class="mb-0 text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total penjualan periode ini</small>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Total Beban</h6>
                        <i class="bx bx-trending-down text-danger"></i>
                    </div>
                    <h3 class="mb-0 text-danger">Rp {{ number_format($totalBeban, 0, ',', '.') }}</h3>
                    <small class="text-muted">Beban pokok + operasional</small>
                </div>
            </div>

            <div class="card mb-3 border-{{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Laba Bersih</h6>
                        <i class="bx bx-dollar-circle {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}"></i>
                    </div>
                    <h3 class="mb-0 {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </h3>
                    <small class="text-muted">
                        @if($labaBersih >= 0)
                            Perusahaan mengalami keuntungan
                        @else
                            Perusahaan mengalami kerugian
                        @endif
                    </small>
                    @if($pendapatan > 0)
                        <div class="mt-2">
                            <span class="badge bg-label-{{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                                Margin: {{ number_format(($labaBersih / $pendapatan) * 100, 2) }}%
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Penjualan vs Pembelian -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tren Penjualan vs Pembelian (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTren" height="80"></canvas>
                </div>
            </div>
        </div>
    </div> --}}
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    const ctx = document.getElementById('chartTren').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.bulan),
            datasets: [
                {
                    label: 'Penjualan',
                    data: chartData.map(d => d.penjualan),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pembelian',
                    data: chartData.map(d => d.pembelian),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection