<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - GlowDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-main: #fbf7f4;
            --bg-sidebar: #f3ece6;
            --sidebar-active: #e4d5c9;
            --text-dark: #3a2e2b;
        }
        body { background-color: var(--bg-main); color: var(--text-dark); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 260px; background-color: var(--bg-sidebar); min-height: 100vh; padding: 20px; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; justify-content: space-between; z-index: 100; }
        .brand-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 25px; }
        .brand-icon { width: 42px; height: 42px; background: #d9bfb0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-item-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: var(--text-dark); text-decoration: none; border-radius: 12px; font-weight: 600; margin-bottom: 6px; transition: all 0.2s; }
        .nav-item-link:hover, .nav-item-link.active { background-color: var(--sidebar-active); color: var(--text-dark); }
        .main-content { margin-left: 260px; padding: 30px; }
        .card-custom { background: #ffffff; border-radius: 16px; border: 1px solid #f0e6df; padding: 24px; }
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
                <li>
                    <a href="{{ route('users.index') }}" class="nav-item-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                </li>
        </ul>
    </div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm rounded-3"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
    </form>
</aside>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Manajemen Pengguna & Kasir</h3>
            <small class="text-muted">Kelola akun Admin dan Staff/Kasir yang memiliki akses ke sistem GlowDesk.</small>
        </div>
        <button type="button" class="btn btn-dark rounded-3 px-3 py-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-1"></i> Tambah Pengguna Baru
        </button>
    </div>

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

    <div class="card card-custom p-4 border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 50px;" class="py-3 px-3 text-center">No</th>
                        <th class="py-3">Nama Pengguna</th>
                        <th class="py-3">Email</th>
                        <th style="width: 120px;" class="py-3 text-center">Role</th>
                        <th style="width: 150px;" class="py-3 text-end px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="text-center px-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if(auth()->id() == $user->id)
                                    <span class="badge bg-soft-info text-info border ms-1" style="font-size: 10px;">Anda</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-center">
                                @if(strtolower($user->role) === 'admin')
                                    <span class="badge bg-danger rounded-pill px-3 py-1">Admin</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1">Staff / Kasir</span>
                                @endif
                            </td>
                            <td class="text-end px-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    @if(auth()->id() != $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit User -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">Edit Pengguna: {{ $user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Alamat Email</label>
                                                <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Role / Hak Akses</label>
                                                <select name="role" class="form-select rounded-3">
                                                    <option value="admin" {{ strtolower($user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="staff" {{ strtolower($user->role) === 'staff' ? 'selected' : '' }}>Staff / Kasir</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Password Baru (Opsional)</label>
                                                <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin mengubah password">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-dark rounded-3 px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pengguna terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan nama pengguna" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Alamat Email</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="email@glowdesk.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Role / Hak Akses</label>
                        <select name="role" class="form-select rounded-3" required>
                            <option value="staff">Staff / Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>