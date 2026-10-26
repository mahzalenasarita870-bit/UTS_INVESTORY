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
            <li>
               <li>
    <a href="{{ route('purchases.index') }}" class="nav-item-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
        <i class="bi bi-download me-2"></i> Barang Masuk
    </a>
</li>
<li>
    <a href="{{ route('sales.index') }}" class="nav-item-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
        <i class="bi bi-cart-check-fill me-2"></i>Transaksi Keluar
    </a>
</li>
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