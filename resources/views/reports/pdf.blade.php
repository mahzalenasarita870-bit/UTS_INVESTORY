<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - GlowDesk</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box { margin-top: 15px; text-align: right; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>GLOWDESK SKINCARE</h2>
        <p>Laporan Penjualan Multi-Item POS</p>
        @if($startDate && $endDate)
            <p>Periode: {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}</p>
        @else
            <p>Periode: Semua Transaksi</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 130px;">No. Struk</th>
                <th style="width: 100px;">Waktu</th>
                <th>Rincian Produk</th>
                <th class="text-right" style="width: 100px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $index => $sale)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $sale->no_struk }}</strong></td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @foreach($sale->items as $item)
                            • {{ $item->product->name ?? 'Produk Dihapus' }} ({{ $item->quantity }} pcs) - Rp {{ number_format($item->subtotal, 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        TOTAL OMZET: Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}
    </div>

</body>
</html>