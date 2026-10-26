<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
public function index()
{
    $products = Product::where('stock', '>', 0)->get();
    $sales = Sale::with('items.product')->latest()->get();

    return view('sales.index', compact('products', 'sales'));
}

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'nominal_bayar' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan Header Transaksi (Tanpa simpan user_id ke database)
            $sale = Sale::create([
                'no_struk'       => 'TRX-OUT-' . strtoupper(uniqid()),
                'total'          => $request->total_belanja,
                'bayar'          => $request->nominal_bayar,
                'kembalian'      => $request->nominal_bayar - $request->total_belanja,
                'payment_method' => $request->payment_method ?? 'Tunai',
            ]);

            // 2. Simpan Detail Item & Kurangi Stok Produk
            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['qty'] * $item['price'],
                ]);

                // Potong Stok Produk
                Product::where('id', $item['product_id'])->decrement('stock', $item['qty']);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function cancelItem($id)
    {
        $saleItem = SaleItem::findOrFail($id);
        $sale = Sale::findOrFail($saleItem->sale_id);

        $product = Product::find($saleItem->product_id);
        if ($product) {
            $product->increment('stock', $saleItem->quantity);
        }

        $sale->total -= $saleItem->subtotal;
        $sale->kembalian = $sale->bayar - $sale->total;
        $sale->save();

        $saleItem->delete();

        if ($sale->items()->count() === 0) {
            $sale->delete();
        }

        return redirect()->back()->with('success', 'Item transaksi berhasil dibatalkan dan stok dikembalikan!');
    }

    public function destroy($id)
    {
        $sale = Sale::with('items')->findOrFail($id);

        foreach ($sale->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        $sale->delete();

        return redirect()->back()->with('success', 'Seluruh transaksi berhasil dibatalkan dan stok dikembalikan!');
    }
}