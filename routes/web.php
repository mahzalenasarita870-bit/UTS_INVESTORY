<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;

// --------------------------------------------------------------------------
// Route Auth (Public - Tanpa Login)
// --------------------------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --------------------------------------------------------------------------
// Route yang Membutuhkan Login (Auth)
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Halaman Dashboard (Dapat diakses Admin & Staff)
    Route::get('/dashboard', function () {
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers  = Supplier::count();
        $totalPurchases  = Purchase::count();

        // Hitung Data Grafik 6 Bulan Terakhir
        $chartLabels = [];
        $salesChartData = [];
        $purchasesChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->translatedFormat('M');

            $salesCount = SaleItem::whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->sum('quantity');

            $purchasesCount = Purchase::whereYear('created_at', $date->year)
                                      ->whereMonth('created_at', $date->month)
                                      ->sum('quantity');

            $salesChartData[] = $salesCount;
            $purchasesChartData[] = $purchasesCount;
        }

        return view('dashboard', compact(
            'lowStockProducts',
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalPurchases',
            'chartLabels',
            'salesChartData',
            'purchasesChartData'
        ));
    })->name('dashboard');

    // Route Master Data (KHUSUS ADMIN SAJA)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);
    });
    
    // Route Transaksi & Laporan (BISA DIAKSES ADMIN & STAFF)
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('purchases', PurchaseController::class);
        Route::resource('sales', SaleController::class);
        
        // Custom Route Pembatalan Item Kasir
        Route::delete('/sales/item/{id}', [SaleController::class, 'cancelItem'])->name('sales.item.cancel');
        
        Route::get('/purchases-index', [PurchaseController::class, 'index'])->name('stock.in');
        Route::get('/sales-index', [SaleController::class, 'index'])->name('stock.out');
        Route::post('/sales-add', [SaleController::class, 'store'])->name('stock.out.add');

        // Laporan Penjualan & Export (PDF & Excel)
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    }); // <-- Penutup Middleware Role Admin & Staff

}); // <-- Penutup Middleware Auth