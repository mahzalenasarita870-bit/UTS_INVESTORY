<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - GlowDesk</title>
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
            max-width: 650px;
            margin: 0 auto;
            overflow: hidden;
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
                    <i class="bi bi-download"></i> Barang Masuk
                </a>
            </li>
            <li>
                <a href="{{ route('stock.out') }}" class="nav-item-link">
                    <i class="bi bi-cart-check-fill"></i> POS / Transaksi Keluar
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
    <div class="mb-4 text-center">
        <h3 class="fw-bold mb-1">Edit Produk Skincare</h3>
        <p class="text-muted small">Perbarui data spesifikasi, harga, dan stok barang</p>
    </div>

    @php
        // Deteksi Nilai Nama Produk
        $pName = $product->name ?? $product->nama_barang ?? $product->nama_produk ?? $product->nama ?? '';
        
        // Deteksi Nilai SKU
        $pSku = $product->sku ?? $product->kode_barang ?? $product->kode ?? '';

        // Deteksi Nilai Harga Dinamis
        $pPrice = $product->price ?? $product->harga_jual ?? $product->harga ?? $product->price_sell ?? 0;

        // Deteksi Nilai Stok
        $pStock = $product->stock ?? $product->stok ?? 0;
        
        // Deteksi Kategori ID
        $pCatId = $product->category_id ?? $product->id_kategori ?? $product->kategori_id ?? '';
    @endphp

    <div class="form-card p-4 shadow-sm">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold small">Kode SKU</label>
                <input type="text" name="sku" class="form-control rounded-3" value="{{ $pSku }}" placeholder="Contoh: BR001">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Nama Produk Skincare</label>
                <input type="text" name="name" class="form-control rounded-3" value="{{ $pName }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Kategori</label>
                <select name="category_id" class="form-select rounded-3">
                    <option value="">-- Pilih Kategori --</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $pCatId == $category->id ? 'selected' : '' }}>
                                {{ $category->name ?? $category->nama_kategori ?? $category->nama }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Harga Jual (Rp)</label>
                    <input type="number" name="price" class="form-control rounded-3" value="{{ (int)$pPrice }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small">Jumlah Stok</label>
                    <input type="number" name="stock" class="form-control rounded-3" value="{{ (int)$pStock }}" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-2">
                <a href="{{ route('products.index') }}" class="btn btn-light rounded-3 px-4 text-muted fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-dark rounded-3 px-4 fw-bold">
                    <i class="bi bi-check-circle me-1"></i> Update Produk
                </button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>