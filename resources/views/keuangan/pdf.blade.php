<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary td {
            width: 25%;
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        .summary .label {
            color: #666;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .summary .value {
            font-size: 14px;
            font-weight: bold;
        }

        .report {
            width: 100%;
            border-collapse: collapse;
        }

        .report td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
        }

        .right {
            text-align: right;
        }

        .section {
            font-weight: bold;
            background: #f5f5f5;
        }

        .indent {
            padding-left: 24px !important;
        }

        .total {
            font-weight: bold;
        }

        .grand-total {
            font-size: 15px;
            font-weight: bold;
            background: #e9f2ff;
        }

        .danger {
            color: #b42318;
        }

        .success {
            color: #067647;
        }

        .footer {
            margin-top: 24px;
            color: #666;
            font-size: 10px;
            text-align: right;
        }

        .note {
            margin-bottom: 18px;
            padding: 10px 12px;
            background: #f8f9fb;
            border: 1px solid #e5e7eb;
        }

        .detail-title {
            margin: 22px 0 8px;
            font-size: 14px;
            font-weight: bold;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
        }

        .detail-table th {
            background: #f5f5f5;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Periode {{ $namaBulan[$bulan] }} {{ $tahun }}</p>
    </div>

    <div class="note">
        Pendapatan diambil dari transaksi penjualan berdasarkan tanggal penjualan, beban pokok diambil dari transaksi pembelian berdasarkan tanggal pembelian, dan beban gaji diambil dari data gaji berstatus Dibayar berdasarkan periode gaji yang masuk ke bulan {{ $namaBulan[$bulan] }} {{ $tahun }}.
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Pendapatan</div>
                <div class="value success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
                <div>{{ $totalTransaksiPenjualan }} transaksi penjualan</div>
            </td>
            <td>
                <div class="label">Beban Pokok</div>
                <div class="value danger">Rp {{ number_format($bebanPokok, 0, ',', '.') }}</div>
                <div>{{ $totalTransaksiPembelian }} transaksi pembelian</div>
            </td>
            <td>
                <div class="label">Beban Gaji</div>
                <div class="value danger">Rp {{ number_format($bebanGaji, 0, ',', '.') }}</div>
                <div>{{ $totalGajiDibayar }} gaji dibayar</div>
            </td>
            <td>
                <div class="label">Laba Bersih</div>
                <div class="value {{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </div>
                <div>Margin {{ number_format($marginBersih, 2) }}%</div>
            </td>
        </tr>
    </table>

    <table class="report">
        <tbody>
            <tr class="section">
                <td>PENDAPATAN</td>
                <td></td>
            </tr>
            <tr>
                <td class="indent">Penjualan</td>
                <td class="right">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total Pendapatan</td>
                <td class="right">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
            </tr>

            <tr class="section">
                <td>BEBAN POKOK PENJUALAN</td>
                <td></td>
            </tr>
            <tr>
                <td class="indent">Pembelian Bahan Baku</td>
                <td class="right danger">(Rp {{ number_format($bebanPokok, 0, ',', '.') }})</td>
            </tr>
            <tr class="total">
                <td>Total Beban Pokok</td>
                <td class="right danger">(Rp {{ number_format($bebanPokok, 0, ',', '.') }})</td>
            </tr>

            <tr class="total">
                <td>LABA KOTOR</td>
                <td class="right {{ $labaKotor >= 0 ? 'success' : 'danger' }}">
                    Rp {{ number_format($labaKotor, 0, ',', '.') }}
                </td>
            </tr>

            <tr class="section">
                <td>BEBAN OPERASIONAL</td>
                <td></td>
            </tr>
            <tr>
                <td class="indent">Beban Gaji</td>
                <td class="right danger">(Rp {{ number_format($bebanGaji, 0, ',', '.') }})</td>
            </tr>
            <tr class="total">
                <td>Total Beban Operasional</td>
                <td class="right danger">(Rp {{ number_format($bebanGaji, 0, ',', '.') }})</td>
            </tr>

            <tr class="grand-total">
                <td>LABA BERSIH</td>
                <td class="right {{ $labaBersih >= 0 ? 'success' : 'danger' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="detail-title">Detail Pendapatan</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 7%;">#</th>
                <th style="width: 28%;">Sumber Data</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 25%;">User</th>
                <th class="right" style="width: 20%;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailPenjualans as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_penjualan }}</td>
                    <td>{{ $item->tanggal_penjualan->format('d-m-Y') }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #666;">Tidak ada detail pendapatan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="detail-title">Detail Beban Pokok</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 7%;">#</th>
                <th style="width: 28%;">Sumber Data</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 25%;">User</th>
                <th class="right" style="width: 20%;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailPembelians as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>Pembelian #{{ $item->id }}</td>
                    <td>{{ $item->tanggal_pembelian->format('d-m-Y') }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #666;">Tidak ada detail beban pokok pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="detail-title">Detail Beban Gaji</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 7%;">#</th>
                <th style="width: 25%;">Karyawan</th>
                <th style="width: 30%;">Periode</th>
                <th style="width: 18%;">Tanggal Bayar</th>
                <th class="right" style="width: 20%;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailGajis as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->karyawan->nama ?? '-' }}</td>
                    <td>{{ $item->periode_awal->format('d-m-Y') }} s/d {{ $item->periode_akhir->format('d-m-Y') }}</td>
                    <td>{{ optional($item->tanggal_bayar)->format('d-m-Y') ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($item->jumlah_gaji, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #666;">Tidak ada detail beban gaji pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
