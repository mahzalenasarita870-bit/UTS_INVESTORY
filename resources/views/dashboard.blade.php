<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analytics - GlowDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-main: #fcf8f5;
            --bg-sidebar: #f5ebe4;
            --sidebar-active: #e8d7cb;
            --text-dark: #332723;
            --brand-accent: #b08974;
            --brand-dark: #3d2f2b;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 100;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: #c5a089;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(197, 160, 137, 0.3);
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            transition: all 0.2s ease-in-out;
        }

        .nav-item-link:hover, .nav-item-link.active {
            background-color: var(--sidebar-active);
            color: var(--text-dark);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        /* Hero Banner Luxury */
        .hero-banner {
            background: linear-gradient(135deg, #3d2f2b 0%, #59453f 100%);
            border-radius: 20px;
            color: #ffffff;
            padding: 28px 32px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(61, 47, 43, 0.15);
        }

        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0e4dc;
            padding: 22px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(61, 47, 43, 0.05) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .icon-box-1 { background: #fff4ec; color: #e67e22; }
        .icon-box-2 { background: #edf7ed; color: #27ae60; }
        .icon-box-3 { background: #eef7fc; color: #2980b9; }
        .icon-box-4 { background: #f6edf9; color: #8e44ad; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div>
        <div class="brand-logo">
            <div class="brand-icon"><i class="bi bi-flower1"></i></div>
            <div>
                <h6 class="mb-0 fw-bold">GlowDesk</h6>
                <small class="text-muted" style="font-size: 11px;">Skincare Inventory & POS</small>
            </div>
        </div>

        <ul class="nav-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-pie-chart-fill"></i> Dashboard
                </a>
            </li>

            @if(auth()->check() && strtolower(auth()->user()->role) === 'admin')
                <li>
                    <a href="{{ route('products.index') }}" class="nav-item-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill"></i> Products Inventory
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" class="nav-item-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i> Categories
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="nav-item-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> Suppliers
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('purchases.index') }}" class="nav-item-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-download"></i> Barang Masuk
                </a>
            </li>
            <li>
                <a href="{{ route('sales.index') }}" class="nav-item-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i> Transaksi Keluar
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="nav-item-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan Penjualan
                </a>
            </li>

            @if(auth()->check() && strtolower(auth()->user()->role) === 'admin')
                <li>
                    <a href="{{ route('users.index') }}" class="nav-item-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm rounded-3">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</aside>

<main class="main-content">
    <!-- HERO BANNER KASIR ELEGANT -->
    <div class="hero-banner d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-white text-dark rounded-pill px-3 py-1 mb-2 fw-semibold" style="font-size: 11px;">
                <i class="bi bi-sparkles text-warning me-1"></i> GlowDesk Executive Overview
            </span>
            <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}! ✨</h3>
            <p class="text-white-50 small mb-0">Pantau pergerakan produk skincare, sisa stok, dan tren penjualan kasir secara realtime.</p>
        </div>
        <div>
            <a href="{{ route('sales.index') }}" class="btn btn-light rounded-3 px-3 py-2 fw-bold text-dark shadow-sm">
                <i class="bi bi-cart-plus me-1 text-success"></i> Transaksi Kasir POS
            </a>
        </div>
    </div>

    <!-- 4 KARTU STATISTIK RINGKAS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-custom border-0 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL PRODUK</small>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalProducts ?? 0 }}</h3>
                    <small class="text-success fw-semibold" style="font-size: 10px;"><i class="bi bi-arrow-up-short"></i> Aktif di Katalog</small>
                </div>
                <div class="stat-icon icon-box-1"><i class="bi bi-box-seam-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom border-0 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">KATEGORI</small>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalCategories ?? 0 }}</h3>
                    <small class="text-muted" style="font-size: 10px;">Terorganisir</small>
                </div>
                <div class="stat-icon icon-box-2"><i class="bi bi-grid-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom border-0 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">SUPPLIER</small>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalSuppliers ?? 0 }}</h3>
                    <small class="text-muted" style="font-size: 10px;">Mitra Terhubung</small>
                </div>
                <div class="stat-icon icon-box-3"><i class="bi bi-truck"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom border-0 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <small class="text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">BARANG MASUK</small>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalPurchases ?? 0 }}</h3>
                    <small class="text-primary fw-semibold" style="font-size: 10px;"><i class="bi bi-clock-history"></i> Riwayat Masuk</small>
                </div>
                <div class="stat-icon icon-box-4"><i class="bi bi-download"></i></div>
            </div>
        </div>
    </div>

    <!-- GRAFIK & ALERTA STOK -->
    <div class="row g-4">
        <!-- GRAFIK UTAMA PERGERAKAN STOK -->
        <div class="col-lg-8">
            <div class="card card-custom border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">Tren Penjualan & Stok</h5>
                        <small class="text-muted" style="font-size: 12px;">Analisis pergerakan item (6 bulan terakhir)</small>
                    </div>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1" style="font-size: 11px;">
                        <i class="bi bi-clock-history me-1"></i> Update Realtime
                    </span>
                </div>
                <div style="height: 310px;" class="mt-2">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- STOK MENIPIS & SHORTCUT -->
        <div class="col-lg-4 d-flex flex-column gap-3">
            <div class="card card-custom border-0 shadow-sm flex-fill">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Stok Menipis (≤ 5)</h6>
                    <span class="badge bg-danger rounded-pill px-2 py-1">{{ count($lowStockProducts) }} Item</span>
                </div>
                <div class="table-responsive" style="max-height: 170px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="py-2">Produk Skincare</th>
                                <th class="text-center py-2">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td><strong class="text-dark">{{ $product->name }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2 py-1">
                                            {{ $product->stock }} pcs
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-shield-check text-success d-block fs-5 mb-1"></i>
                                        Semua persediaan produk terhitung aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SHORTCUT KASIR -->
            <div class="card card-custom border-0 shadow-sm p-3 bg-light">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon bg-white text-dark rounded-circle border shadow-sm fs-5">
                            <i class="bi bi-upc-scan text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">POS Scanner Active</h6>
                            <small class="text-muted" style="font-size: 11px;">Barcode & SKU Reader Siap</small>
                        </div>
                    </div>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-dark rounded-3 px-3">Buka Kasir</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Gradient Warna Smooth
    const gradientSales = ctx.createLinearGradient(0, 0, 0, 300);
    gradientSales.addColorStop(0, 'rgba(46, 204, 113, 0.25)');
    gradientSales.addColorStop(1, 'rgba(46, 204, 113, 0.0)');

    const gradientPurchases = ctx.createLinearGradient(0, 0, 0, 300);
    gradientPurchases.addColorStop(0, 'rgba(52, 152, 219, 0.25)');
    gradientPurchases.addColorStop(1, 'rgba(52, 152, 219, 0.0)');

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [
                {
                    label: 'Produk Terjual (Pcs)',
                    data: {!! json_encode($salesChartData ?? []) !!},
                    borderColor: '#2ecc71',
                    backgroundColor: gradientSales,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#2ecc71',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                },
                {
                    label: 'Barang Masuk (Pcs)',
                    data: {!! json_encode($purchasesChartData ?? []) !!},
                    borderColor: '#3498db',
                    backgroundColor: gradientPurchases,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.35
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: { family: 'Segoe UI', size: 12, weight: '600' }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f5ebe4' }
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>