<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - MahzaGlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #f28482; color: white; }
        .sidebar a { color: white; text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; margin-bottom: 5px; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { background-color: #e07a5f; }
        .header-bar { background-color: #e07a5f; color: white; padding: 10px 20px; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>

<div class="header-bar d-flex align-items-center">
    <i class="bi bi-shop me-2 fs-5"></i> MahzaGlow Skincare, JL. Merdeka No. 25, Kota Lhokseumawe, Aceh
</div>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar p-3">
            <div class="fw-bold fs-5 text-center mb-3"><i class="bi bi-bag-heart-fill me-2"></i>MAHZAGLOW</div>
            <hr class="text-white-50">
            <ul class="nav nav-pills flex-column">
                @can('admin-access')
                    <li><a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li><a href="{{ route('products.index') }}"><i class="bi bi-box-seam me-2"></i> Data Master</a></li>
                    <li><a href="{{ route('stock.in') }}"><i class="bi bi-box-arrow-in-down me-2"></i> Barang Masuk</a></li>
                @endcan

                <li><a href="{{ route('stock.out') }}"><i class="bi bi-file-earmark-text me-2"></i> Barang Keluar</a></li>
                <li><a href="{{ route('sales.create') }}" class="active"><i class="bi bi-cart-check me-2"></i> Transaksi Jual</a></li>
            </ul>

            <div class="mt-auto pt-4 border-top border-white-50 text-center">
                <small class="d-block mb-2"><i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'User' }}</small>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-secondary">Kasir / Form Transaksi Jual</h3>
                <span class="badge bg-light text-dark p-2 border"><i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}</span>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-3 mb-3" style="border-radius: 12px;">
                            <h5 class="fw-bold mb-3"><i class="bi bi-bag-plus me-2"></i>Pilih Produk</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Stok Available</th>
                                            <th style="width: 150px;">Jumlah Beli</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                        <tr>
                                            <td class="fw-semibold">{{ $product->name }}</td>
                                            <td>Rp {{ number_format($product->price ?? $product->selling_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $product->stock > 5 ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $product->stock }} pcs
                                                </span>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="items[{{ $product->id }}]" 
                                                       class="form-control form-control-sm item-qty" 
                                                       data-price="{{ $product->price ?? $product->selling_price }}"
                                                       min="0" 
                                                       max="{{ $product->stock }}" 
                                                       value="0">
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada produk tersedia.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">
                            <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Ringkasan Transaksi</h5>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">TOTAL BAYAR (RP)</label>
                                <input type="text" id="total_display" class="form-control form-control-lg fw-bold text-success" value="Rp 0" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">UANG DITERIMA (RP)</label>
                                <input type="number" name="pay_amount" class="form-control form-control-lg" placeholder="0" required>
                            </div>
                            <button type="submit" class="btn text-white w-100 py-3 text-uppercase fw-bold shadow-sm" style="background-color: #f28482; border: none;">
                                <i class="bi bi-printer me-2"></i>Simpan & Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInputs = document.querySelectorAll('.item-qty');
        const totalDisplay = document.getElementById('total_display');

        function calculateTotal() {
            let total = 0;
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.getAttribute('data-price')) || 0;
                total += (qty * price);
            });
            totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
    });
</script>
</body>
</html>