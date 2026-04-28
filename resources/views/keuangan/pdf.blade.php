<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 12px;
            line-height: 1.45;
            margin: 28px 34px;
        }

        h1,
        p {
            margin: 0;
        }

        .title {
            text-align: center;
            margin-bottom: 12px;
        }

        .title h1 {
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .period {
            font-size: 13px;
            margin-bottom: 18px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .report-table thead th {
            border-top: 1px solid #777;
            border-bottom: 1px solid #777;
            padding: 8px 6px;
            text-align: left;
            font-size: 12px;
        }

        .report-table tbody td {
            padding: 6px;
            vertical-align: top;
        }

        .report-table tfoot th {
            border-top: 1px solid #777;
            padding: 10px 6px 6px;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 6px;
        }

        .summary-row {
            margin-bottom: 6px;
        }

        .status {
            font-weight: 700;
        }

        .positive {
            color: #1f7a1f;
        }

        .negative {
            color: #b42318;
        }

        .empty {
            text-align: center;
            color: #666;
            padding: 16px 0;
        }
    </style>
</head>

<body>
    <div class="title">
        <h1>Laporan Keuangan </h1>
    </div>

    <p class="period"><strong>Periode:</strong> {{ $periodeMulai->format('d/m/Y') }} -
        {{ $periodeSelesai->format('d/m/Y') }}
    </p>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 35%;">Keterangan</th>
                <th class="text-right" style="width: 16%;">Pemasukan</th>
                <th class="text-right" style="width: 16%;">Pengeluaran</th>
                <th class="text-right" style="width: 18%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiKeuangan as $item)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td class="text-right">
                        {{ $item['pemasukan'] > 0 ? 'Rp' . number_format($item['pemasukan'], 0, ',', '.') . ',00' : '-' }}
                    </td>
                    <td class="text-right">
                        {{ $item['pengeluaran'] > 0 ? 'Rp' . number_format($item['pengeluaran'], 0, ',', '.') . ',00' : '-' }}
                    </td>
                    <td class="text-right">
                        Rp{{ number_format($item['saldo'], 0, ',', '.') }},00
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">Belum ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">TOTAL</th>
                <th class="text-right">Rp{{ number_format($pendapatan, 0, ',', '.') }},00</th>
                <th class="text-right">Rp{{ number_format($totalBeban, 0, ',', '.') }},00</th>
                <th class="text-right">Rp{{ number_format($labaBersih, 0, ',', '.') }},00</th>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-row">
            <strong>Sisa Saldo:</strong>
            Rp{{ number_format($labaBersih, 0, ',', '.') }},00
        </div>
        <div class="summary-row">
            <strong>Kondisi Keuangan:</strong>
            <span class="status {{ $labaBersih >= 0 ? 'positive' : 'negative' }}">{{ $kondisiKeuangan }}</span>
        </div>
    </div>
</body>

</html>