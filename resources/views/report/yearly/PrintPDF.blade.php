<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Tahunan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1 style="text-align: center;">LAPORAN PENJUALAN TAHUNAN</h1>
    <p>Periode: {{ $period }}</p>

    <table>
        <tr>
            <th>Kode Transaksi</th>
            <th>Tanggal</th>
            <th>Katalog</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Jumlah</th>
        </tr>
        @php
            $totalQuantity = 0;
        @endphp
          @forelse ($transactions as $transaction)
            @foreach ($transaction->details as $detail)
                <tr>
                    <td>{{ $transaction->code }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('l, d F Y') }}</td>
                    <td>{{ $detail->product->catalog->name ?? '-' }}</td>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ 'Rp ' . number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ 'Rp ' . number_format($detail->total_price, 0, ',', '.') }}</td>
                </tr>
                @php
                    $totalQuantity += $detail->quantity;
                @endphp
            @endforeach
        @empty
            <tr>
                <td colspan="7">Data tidak tersedia</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="5"><strong>Total</strong></td>
            <td><strong>{{ $totalQuantity }}</strong></td>
            <td colspan="1">
                <strong>{{ 'Rp ' . number_format($transactions->sum(fn($t) => $t->details->sum('total_price')), 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

</body>

</html>
