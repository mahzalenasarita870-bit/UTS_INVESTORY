<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MahzaGlow POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --mg-primary: #e07a5f;
            --mg-secondary: #f28482;
            --mg-bg: #f8f9fa;
            --mg-dark: #3d405b;
        }

        body { 
            background-color: var(--mg-bg); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--mg-dark);
        }

        .header-bar { 
            background-color: var(--mg-primary); 
            color: white; 
            padding: 12px 24px; 
            font-weight: 600; 
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .sidebar { 
            min-height: calc(100vh - 48px); 
            background-color: var(--mg-secondary); 
            color: white; 
            border-top-right-radius: 12px;
        }

        .sidebar .nav-link { 
            color: rgba(255, 255, 255, 0.9); 
            padding: 12px 16px; 
            border-radius: 8px; 
            margin-bottom: 6px; 
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            background-color: var(--mg-primary); 
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .table thead {
            background-color: #f1f3f5;
            color: var(--mg-dark);
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header-bar d-flex justify-content-between align-items-center">
    <div>
        <i class="bi bi-shop me-2 fs-5"></i> MahzaGlow Skincare — JL. Merdeka No. 25, Kota Lhokseumawe, Aceh
    </div>
    <div class="small opacity-75">
        <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar p-3 d-flex flex-column">
            <div class="fw-bold fs-5 text-center my-2 text-white">
                <i class="bi bi-bag-heart-fill me-2"></i>MAHZAGLOW
            </div>
            <hr class="text-white-50">

            <ul class="nav nav-pills flex-column mb-auto">
                @can('admin-access')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam me-2"></i> Data Master
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('stock.in') }}" class="nav-link {{ request()->is('stock-in*') ? 'active' : '' }}">
                            <i class="bi bi-box-arrow-in-down me-2"></i> Barang Masuk
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('stock.out') }}" class="nav-link {{ request()->is('stock-out*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text me-2"></i> Barang Keluar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sales.create') }}" class="nav-link {{ request()->is('sales/create') ? 'active' : '' }}">
                        <i class="bi bi-cart-check me-2"></i> Transaksi Jual
                    </a>
                </li>
            </ul>

            <div class="pt-3 border-top border-white-50 text-center mt-3">
                <div class="small mb-2 fw-semibold"><i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'User' }}</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="col-md-10 p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>