<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Inventory - GlowDesk</title>
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

            {{-- Menu ini HANYA muncul jika akun yang login bernilai ADMIN --}}
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

            {{-- Menu ini muncul untuk ADMIN & STAFF --}}
            <li>
                <a href="{{ route('purchases.index') }}" class="nav-item-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-download"></i> Barang Masuk
                </a>
            </li>
            <li>
                <a href="{{ route('sales.index') }}" class="nav-item-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i>Transaksi Keluar
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="nav-item-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan Penjualan
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="nav-item-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> User Management
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
        <div>
            <h3 class="fw-bold mb-1">Products Inventory</h3>
            <p class="text-muted small mb-0">Kelola daftar produk skincare dan informasi stok</p>
        </div>
        <button type="button" class="btn btn-dark rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-card shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Stok Produk</h6>
            <span class="badge badge-soft">{{ count($products) }} Total Produk</span>
        </div>

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th class="text-end" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $attrs = $product->getAttributes();

                        // Deteksi Nama Produk
                        $pName = $attrs['name'] ?? $attrs['nama_barang'] ?? $attrs['nama_produk'] ?? $attrs['nama'] ?? 'Produk';
                        
                        // Deteksi SKU
                        $pSku = $attrs['sku'] ?? $attrs['kode_barang'] ?? $attrs['kode'] ?? ('BR00' . $product->id);
                        
                        // Deteksi HARGA DINAMIS
                        $pPrice = 0;
                        foreach (['price', 'harga_jual', 'harga', 'price_sell', 'selling_price'] as $pCol) {
                            if (isset($attrs[$pCol]) && $attrs[$pCol] > 0) {
                                $pPrice = $attrs[$pCol];
                                break;
                            }
                        }

                        // Deteksi STOK
                        $pStock = $attrs['stock'] ?? $attrs['stok'] ?? 0;
                        
                        // Deteksi Kategori
                        $pCatId = $attrs['category_id'] ?? $attrs['id_kategori'] ?? $attrs['kategori_id'] ?? '';
                        $cName = $product->category->name ?? $product->category->nama_kategori ?? $product->category->nama ?? 'Uncategorized';
                    @endphp
                    <tr>
                        <td><code class="text-danger fw-bold">{{ $pSku }}</code></td>
                        <td class="fw-bold text-dark">{{ $pName }}</td>
                        <td><span class="badge bg-light text-dark border rounded-pill px-3">{{ $cName }}</span></td>
                        <td class="fw-bold text-dark">Rp {{ number_format($pPrice, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $pStock > 5 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $pStock > 5 ? 'text-success' : 'text-danger' }} border rounded-pill px-3">
                                {{ $pStock }} pcs
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDIT PRODUK -->
                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold modal-title">Edit Produk Skincare</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('products.update', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Kode SKU</label>
                                            <input type="text" name="sku" class="form-control rounded-3" value="{{ $pSku }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Nama Produk</label>
                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $pName }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Kategori</label>
                                            <select name="category_id" class="form-select rounded-3">
                                                <option value="">-- Pilih Kategori --</option>
                                                @if(isset($categories))
                                                    @foreach ($categories as $cat)
                                                        @php
                                                            $catName = $cat->name ?? $cat->nama_kategori ?? $cat->nama;
                                                        @endphp
                                                        <option value="{{ $cat->id }}" {{ $pCatId == $cat->id ? 'selected' : '' }}>{{ $catName }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Harga Jual (Rp)</label>
                                                <input type="number" name="price" class="form-control rounded-3" value="{{ (int)$pPrice }}" required>
                                                <input type="hidden" name="harga_jual" value="{{ (int)$pPrice }}">
                                                <input type="hidden" name="harga" value="{{ (int)$pPrice }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Jumlah Stok</label>
                                                <input type="number" name="stock" class="form-control rounded-3" value="{{ (int)$pStock }}" required>
                                                <input type="hidden" name="stok" value="{{ (int)$pStock }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark rounded-3 fw-bold">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada produk skincare terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

<!-- MODAL TAMBAH PRODUK -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold modal-title">Tambah Produk Skincare Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kode SKU</label>
                        <input type="text" name="sku" class="form-control rounded-3" placeholder="Contoh: BR001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Produk</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: Glad to Glow Blueberry Moisturizer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori</label>
                        <select name="category_id" class="form-select rounded-3">
                            <option value="">-- Pilih Kategori --</option>
                            @if(isset($categories))
                                @foreach ($categories as $cat)
                                    @php
                                        $catName = $cat->name ?? $cat->nama_kategori ?? $cat->nama;
                                    @endphp
                                    <option value="{{ $cat->id }}">{{ $catName }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Harga Jual (Rp)</label>
                            <input type="number" name="price" class="form-control rounded-3" placeholder="Contoh: 55000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jumlah Stok</label>
                            <input type="number" name="stock" class="form-control rounded-3" placeholder="Contoh: 10" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 fw-bold">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>