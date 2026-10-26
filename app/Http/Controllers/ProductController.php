<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku'         => 'nullable|string|max:50',
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        Product::create([
            'sku'         => $request->sku ?? ('BR00' . (Product::max('id') + 1)),
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'price'       => $request->price, // Menggunakan nama kolom 'price'
            'stock'       => $request->stock, // Menggunakan nama kolom 'stock'
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sku'         => 'nullable|string|max:50',
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'sku'         => $request->sku,
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'stock'       => $request->stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}