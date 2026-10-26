<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Masuk - GlowDesk</title>
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

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0e6df;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .table-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0e6df;
            padding: 24px;
        }

        .badge-soft {
            background-color: #f5eeeb;
            color: #8c7b75;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
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

                {{-- Menu Master Data ini HANYA TAMPIL untuk Admin --}}
                @if(auth()->check() && auth()->user()->role == 'admin')
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

                {{-- Menu ini tampil untuk Admin dan Staff --}}
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

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100 btn-sm rounded-3">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Manajemen Barang Masuk</h3>
                <p class="text-muted mb-0">Catat transaksi pembelian stok dari supplier dan penambahan stok otomatis.</p>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Form Transaksi Masuk --}}
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-plus-circle me-2"></i>Catat Barang Masuk / Restok Produk
            </h5>
            <p class="text-muted small mb-4">Pilih produk, supplier, dan masukkan jumlah stok yang baru diterima</p>

            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pilih Produk Skincare</label>
                        <select name="product_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Stok Saat Ini: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pilih Supplier</label>
                        <select name="supplier_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jumlah Masuk (Qty)</label>
                        <input type="number" name="quantity" class="form-control rounded-3" placeholder="Masukkan jumlah barang" min="1" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-dark rounded-3 px-4 py-2">
                        <i class="bi bi-download me-2"></i>Simpan Barang Masuk
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Riwayat Barang Masuk --}}
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Riwayat Barang Masuk</h5>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    {{ $purchases->count() }} Total Transaksi
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-3">No</th>
                            <th scope="col" class="py-3">No. Transaksi / Tanggal</th>
                            <th scope="col" class="py-3">Detail Produk</th>
                            <th scope="col" class="py-3">Supplier</th>
                            <th scope="col" class="py-3 text-center">Jumlah Masuk</th>
                            <th scope="col" class="py-3 text-end px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $index => $purchase)
                            <tr>
                                <td class="px-3 fw-bold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $purchase->created_at->format('Y-m-d H:i:s') }}</div>
                                    <small class="text-success fw-semibold">TRX-IN-{{ $purchase->id }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $purchase->product->name ?? 'Produk Dihapus' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $purchase->supplier->name ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                        +{{ $purchase->quantity }} pcs
                                    </span>
                                </td>
                                <td class="text-end px-3">
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok akan dikurangi kembali.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada riwayat transaksi barang masuk.
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