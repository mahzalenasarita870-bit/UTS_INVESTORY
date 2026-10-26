<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Sale::with('items.product')->latest();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Struk',
            'Waktu Penjualan',
            'Rincian Produk',
            'Metode Pembayaran',
            'Total Belanja (Rp)',
            'Nominal Bayar (Rp)',
            'Kembalian (Rp)',
        ];
    }

    public function map($sale): array
    {
        $itemsText = [];
        foreach ($sale->items as $item) {
            $pName = $item->product->name ?? 'Produk Dihapus';
            $itemsText[] = "{$pName} ({$item->quantity} pcs) - Rp " . number_format($item->subtotal, 0, ',', '.');
        }

        return [
            $sale->no_struk,
            $sale->created_at->format('d/m/Y H:i'),
            implode(" | ", $itemsText),
            $sale->payment_method ?? 'Tunai',
            $sale->total,
            $sale->bayar,
            $sale->kembalian,
        ];
    }
}