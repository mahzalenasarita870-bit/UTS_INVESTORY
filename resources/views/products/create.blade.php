<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - GlowDesk</title>
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

        .form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f0e6df;
            padding: 28px;
            max-width: 750px;
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
                <a href="{{ route('dashboard') }}" class="nav-item-link">
                    <i class="bi bi-pie-chart-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}" class="nav-item-link active">
                    <i class="bi bi-box-seam-fill"></i> Products Inventory
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}" class="nav-item-link">
                    <i class="bi bi-grid-fill"></i> Categories
                </a>
            </li>
            <li>
                <a href="{{ route('suppliers.index') }}" class="nav-item-link">
                    <i class="bi bi-truck"></i> Suppliers
                </a>
            </li>
            <li>
                <a href="{{ route('purchases.index') }}" class="nav-item-link">
                    <i class="bi bi-bag-check-fill"></i> POS / Sales Out
                </a>
            </li>
        </ul>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm rounded-3">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</aside>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Tambah Produk Skincare</h3>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card shadow-sm">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Kode Barang (SKU)</label>
                    <input type="text" name="sku" class="form-control" placeholder="Contoh: BR001" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama / Kualitas Skincare</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Glow Serum 30ml" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name ?? $category->nama_kategori ?? $category->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Harga Beli (Rp)</label>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Harga Jual (Rp)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jumlah Stok Awal</label>
                    <input type="number" name="stock" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Stok Minimum Alert</label>
                    <input type="number" name="min_stock" class="form-control" value="3">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4 rounded-3">Batal</a>
                <button type="submit" class="btn btn-dark px-4 rounded-3 fw-bold">Simpan Produk</button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>