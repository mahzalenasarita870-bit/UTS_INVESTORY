<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['product', 'supplier'])->latest()->get();
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('purchases.index', compact('purchases', 'products', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'quantity'       => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
        ]);

        // Ambil data produk
        $product = Product::findOrFail($request->product_id);

        // Jika harga beli kosong, gunakan harga dari request atau 0
        $purchasePrice = $request->purchase_price ?? $request->price ?? 0;

        // 1. Simpan transaksi barang masuk
        Purchase::create([
            'product_id'     => $request->product_id,
            'supplier_id'    => $request->supplier_id,
            'quantity'       => $request->quantity,
            'purchase_price' => $purchasePrice,
        ]);

        // 2. Tambahkan stok produk otomatis
        $product->increment('stock', $request->quantity);

        return redirect()->route('purchases.index')->with('success', 'Transaksi barang masuk berhasil dicatat!');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        
        // Kurangi stok kembali jika riwayat dihapus
        $product = Product::find($purchase->product_id);
        if ($product) {
            $product->decrement('stock', $purchase->quantity);
        }

        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Riwayat transaksi berhasil dihapus!');
    }
}