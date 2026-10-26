<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - GlowDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-main: #fbf7f4;
            --bg-sidebar: #f3ece6;
            --sidebar-active: #e4d5c9;
            --text-dark: #3a2e2b;
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
            background: #d9bfb0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
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
            transition: all 0.2s;
        }

        .nav-item-link:hover, .nav-item-link.active {
            background-color: var(--sidebar-active);
            color: var(--text-dark);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .card-custom {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0e6df;
            padding: 24px;
        }

        /* Styling Cetak Laporan */
        @media print {
            .sidebar, 
            .no-print, 
            button, 
            form {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }

            .card, .card-custom {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 15mm;
            }
        }
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
            <li>
{{-- Menu User Management KHUSUS ADMIN (DIBUNGKUS IF ADMIN) --}}
            @if(auth()->check() && strtolower(auth()->user()->role) === 'admin')
                    <a href="{{ route('users.index') }}" class="nav-item-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="no-print">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm rounded-3">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Header Page dengan Flexbox Responsif -->
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-0">Laporan Penjualan Skincare</h3>
            <small class="text-muted">Rekapitulasi transaksi, total pendapatan (omzet), dan volume item terjual.</small>
        </div>
        <div class="d-flex gap-2 no-print flex-wrap">
            <a href="{{ route('reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success rounded-3 px-3 py-2 btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
            </a>
            <a href="{{ route('reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger rounded-3 px-3 py-2 btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
            </a>
            <button type="button" class="btn btn-dark rounded-3 px-3 py-2 btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Form Filter Periode Tanggal --}}
    <div class="card card-custom p-4 mb-4 no-print border-0 shadow-sm">
        <h6 class="fw-bold mb-3"><i class="bi bi-filter me-2"></i>Filter Periode Tanggal</h6>
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control rounded-3" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Tanggal Sampai</label>
                <input type="date" name="end_date" class="form-control rounded-3" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark rounded-3 px-3 w-100">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary rounded-3 px-3">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Widget Ringkasan Statistik Laporan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-3 border-start border-4 border-dark border-0 shadow-sm">
                <small class="text-muted fw-semibold">TOTAL TRANSAKSI</small>
                <h4 class="fw-bold text-dark mb-0 mt-1">{{ $sales->count() }} Transaksi</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-3 border-start border-4 border-success border-0 shadow-sm">
                <small class="text-muted fw-semibold">TOTAL PENDAPATAN (OMZET)</small>
                <h4 class="fw-bold text-success mb-0 mt-1">Rp {{ number_format($sales->sum('total'), 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-3 border-start border-4 border-primary border-0 shadow-sm">
                <small class="text-muted fw-semibold">TOTAL PRODUK TERJUAL</small>
                <h4 class="fw-bold text-primary mb-0 mt-1">
                    {{ $sales->sum(function($sale) { return $sale->items->sum('quantity'); }) }} Pcs
                </h4>
            </div>
        </div>
    </div>

    {{-- Tabel Data Laporan Penjualan --}}
    <div class="card card-custom p-4 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Rincian Transaksi Penjualan</h5>
            @if($startDate && $endDate)
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    Periode: {{ $startDate }} s/d {{ $endDate }}
                </span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 40px;" class="py-3 px-3 text-center">No</th>
                        <th style="width: 160px;" class="py-3">No. Struk</th>
                        <th style="width: 140px;" class="py-3 text-nowrap">Waktu Penjualan</th>
                        <th class="py-3">Rincian Produk Skincare</th>
                        <th style="width: 110px;" class="py-3 text-center">Metode</th>
                        <th style="width: 150px;" class="py-3 text-end px-3 text-nowrap">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $index => $sale)
                        <tr>
                            <td class="text-center px-3 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="text-nowrap"><span class="fw-bold text-danger">{{ $sale->no_struk }}</span></td>
                            <td class="text-nowrap text-muted" style="font-size: 0.8rem;">
                                {{ $sale->created_at->format('d/m/Y') }}<br>
                                <small class="text-secondary">{{ $sale->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($sale->items as $item)
                                        <div class="bg-light rounded px-2 py-1" style="font-size: 0.825rem;">
                                            • <strong>{{ $item->product->name ?? 'Produk Dihapus' }}</strong> 
                                            <span class="text-muted">({{ $item->quantity }} pcs)</span> 
                                            - <span class="fw-semibold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 11px;">
                                    {{ $sale->payment_method ?? 'Tunai' }}
                                </span>
                            </td>
                            <td class="text-end px-3 fw-bold text-success text-nowrap">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Tidak ada data transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>