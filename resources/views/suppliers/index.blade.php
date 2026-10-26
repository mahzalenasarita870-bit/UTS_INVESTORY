<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - GlowDesk</title>
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
    <!-- HEADER HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Daftar Supplier Mitra</h3>
            <p class="text-muted small mb-0">Kelola daftar kontak pemasok dan jaringan distributor skincare kamu</p>
        </div>
        <button type="button" class="btn btn-dark rounded-3 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Supplier Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- STATISTIK KARTU (PEMANTIS DESAIN) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ count($suppliers) }}</h4>
                    <small class="text-muted">Total Supplier Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-telephone-check"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ count($suppliers->filter(fn($s) => !empty($s->phone || $s->no_hp))) }}</h4>
                    <small class="text-muted">Kontak Terverifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ count($suppliers->filter(fn($s) => !empty($s->address || $s->alamat))) }}</h4>
                    <small class="text-muted">Alamat Terdaftar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL SUPPLIER -->
    <div class="table-card shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2"></i>Daftar Supplier Terdaftar</h6>
            <span class="badge badge-soft">{{ count($suppliers) }} Supplier</span>
        </div>

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Supplier</th>
                    <th>No. Telepon / HP</th>
                    <th>Alamat</th>
                    <th class="text-end" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $index => $supplier)
                    @php
                        $attrs = $supplier->getAttributes();
                        $sName = $attrs['name'] ?? $attrs['nama_supplier'] ?? $attrs['nama'] ?? 'Supplier';
                        $sPhone = $attrs['phone'] ?? $attrs['no_hp'] ?? $attrs['telepon'] ?? '-';

                        $sAddress = null;
                        foreach (['address', 'alamat', 'lokasi', 'deskripsi', 'detail'] as $colKey) {
                            if (!empty($attrs[$colKey])) {
                                $sAddress = $attrs[$colKey];
                                break;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold text-dark"><i class="bi bi-building text-muted me-2"></i>{{ $sName }}</td>
                        <td><i class="bi bi-telephone text-muted me-1"></i> {{ $sPhone }}</td>
                        <td>
                            @if(!empty($sAddress))
                                <i class="bi bi-geo-alt text-muted me-1"></i> {{ $sAddress }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDIT SUPPLIER -->
                    <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold modal-title">Edit Data Supplier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Nama Supplier</label>
                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $sName }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">No. Telepon / HP</label>
                                            <input type="text" name="phone" class="form-control rounded-3" value="{{ $sPhone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Alamat Supplier</label>
                                            <textarea name="address" class="form-control rounded-3" rows="3" placeholder="Masukkan alamat supplier">{{ $sAddress }}</textarea>
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
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data supplier terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

<!-- MODAL TAMBAH SUPPLIER -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold modal-title">Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Supplier</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: PT Beauty Indonesia" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">No. Telepon / HP</label>
                        <input type="text" name="phone" class="form-control rounded-3" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Alamat Supplier</label>
                        <textarea name="address" class="form-control rounded-3" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 fw-bold">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>