<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalPurchases = Purchase::count();

        // Cari stok menipis (<= 5)
        $stockColumn = Schema::hasColumn('products', 'stock') ? 'stock' : 'stok';
        $lowStockProducts = Product::where($stockColumn, '<=', 5)->get();

        return view('dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalSuppliers', 
            'totalPurchases',
            'lowStockProducts'
        ));
    }
}