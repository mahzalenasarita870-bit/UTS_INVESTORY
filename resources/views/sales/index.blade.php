<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - GlowDesk</title>
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

        .receipt-container {
            background: #fff;
            padding: 15px;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
        }

        .receipt-dashed-line {
            border-bottom: 1px dashed #444;
            margin: 8px 0;
        }

        .btn-cancel-item {
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 4px;
            line-height: 1.2;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #receiptModal, 
            #receiptModal * {
                visibility: visible !important;
            }

            #receiptModal {
                position: absolute !important;
                left: 50% !important;
                top: 20px !important;
                transform: translateX(-50%) !important;
                width: 320px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                background: white !important;
            }

            .modal-dialog {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-content {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: auto;
                margin: 0mm;
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

            @if(auth()->check() && strtolower(auth()->user()->role) === 'admin')
                <li>
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
            <h3 class="fw-bold mb-0">POS Kasir Penjualan</h3>
            <small class="text-muted">Kelola penjualan skincare, scan barcode/SKU, keranjang kasir, dan cetak struk.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-bag-check me-1 text-success"></i> {{ $sales->count() }} Transaksi Selesai
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- FORM PILIH & SCAN PRODUK -->
        <div class="col-lg-6">
            <div class="card p-4 h-100 d-flex flex-column justify-content-between border-0 shadow-sm">
                <div>
                    <!-- WIDGET SCAN BARCODE AUTOMATIS -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">
                            <i class="bi bi-upc-scan me-1"></i> Scan Barcode / Cari SKU Produk
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-barcode text-muted"></i></span>
                            <input type="text" id="barcodeInput" class="form-control border-start-0" placeholder="Arahkan scanner atau ketik SKU (cth: BR002)..." autofocus>
                            <button class="btn btn-dark px-3" type="button" id="btnScanAdd">
                                <i class="bi bi-plus-lg me-1"></i> Tambah
                            </button>
                        </div>
                        <small class="text-muted mt-1" style="font-size: 11px;">*Tekan <strong>Enter</strong> untuk memasukkan barang ke keranjang otomatis.</small>
                    </div>

                    <hr class="my-3">

                    <h6 class="fw-bold mb-2"><i class="bi bi-bag-plus me-2"></i>Pilih Produk Manual</h6>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Cari & Pilih Produk</label>
                        <input type="text" id="searchInput" class="form-control rounded-3 form-control-sm mb-2" placeholder="🔍 Ketik nama produk skincare..." onkeyup="filterProducts()">
                        <select id="productSelect" class="form-select rounded-3" size="4" style="font-size: 0.9rem;">
                            @foreach ($products as $product)
                                @php
                                    $attrs = $product->getAttributes();
                                    $pName = $attrs['name'] ?? $attrs['nama_barang'] ?? $attrs['nama_produk'] ?? 'Produk';
                                    $pSku = $attrs['sku'] ?? $attrs['barcode'] ?? $attrs['kode_barang'] ?? ('BR00' . $product->id);
                                    $pPrice = $product->price ?? $attrs['harga_jual'] ?? $attrs['harga'] ?? 0;
                                    $pStock = $product->stock ?? $attrs['stok'] ?? 0;
                                @endphp
                                <option value="{{ $product->id }}" data-name="{{ $pName }}" data-price="{{ $pPrice }}" data-stock="{{ $pStock }}" data-barcode="{{ $pSku }}">
                                    [{{ $pSku }}] {{ $pName }} (Stok: {{ $pStock }} | Rp {{ number_format($pPrice, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Harga Satuan</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" id="displayPrice" class="form-control bg-light" readonly value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Jumlah Beli (Qty)</label>
                            <input type="number" id="quantityInput" class="form-control form-control-sm rounded-3" value="1" min="1">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-dark w-100 rounded-3 py-2 fw-semibold" onclick="addToCart()">
                    <i class="bi bi-plus-circle me-1"></i> Masukkan Keranjang
                </button>
            </div>
        </div>

        <!-- RINGKASAN KERANJANG & BAYAR -->
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm" style="background-color: #fcf9f6;">
                <form action="{{ route('sales.store') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-cart3 me-2"></i>Keranjang Kasir</h6>
                        <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0" onclick="clearCart()">
                            <i class="bi bi-trash me-1"></i>Reset
                        </button>
                    </div>

                    <div class="table-responsive mb-3" style="max-height: 160px; overflow-y: auto;">
                        <table class="table table-sm align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody id="cartTableBody">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Keranjang kosong. Pilih produk di sebelah kiri.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="hiddenInputsContainer"></div>

                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">Total Belanja:</span>
                            <span class="fw-bold fs-4 text-success" id="grandTotalText">Rp 0</span>
                            <input type="hidden" name="total_belanja" id="totalBelanjaInput" value="0">
                        </div>

                        <!-- METODE PEMBAYARAN MULTI-PAYMENT -->
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Metode Bayar</label>
                                <select name="payment_method" id="paymentMethodSelect" class="form-select form-select-sm rounded-3">
                                    <option value="Tunai">Tunai / Cash</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="Debit">Kartu Debit</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Uang Bayar (Rp)</label>
                                <input type="number" name="nominal_bayar" id="cashInput" class="form-control form-control-sm rounded-3" placeholder="Nominal Rp" oninput="calculateChange()">
                            </div>
                        </div>

                        <div id="paymentAlert" class="text-danger small fw-semibold mb-2" style="display: none;">
                            <i class="bi bi-exclamation-circle me-1"></i> Nominal pembayaran masih kurang!
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 bg-white px-3 py-2 rounded border">
                            <small class="text-muted fw-semibold">Kembalian:</small>
                            <span class="fw-bold text-primary" id="changeText">Rp 0</span>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 rounded-3 py-2 fw-semibold" id="checkoutBtn" disabled>
                            <i class="bi bi-check2-circle me-1"></i> Selesaikan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABEL RIWAYAT TRANSAKSI --}}
    <div class="card p-4 border-0 shadow-sm">
        <h6 class="fw-bold mb-3">Riwayat Transaksi Penjualan</h6>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th style="width: 50px;" class="py-3 px-3 text-center">No</th>
                        <th style="width: 170px;" class="py-3">No. Struk</th>
                        <th style="width: 130px;" class="py-3">Waktu</th>
                        <th class="py-3">Detail Produk</th>
                        <th style="width: 110px;" class="py-3 text-center">Metode</th>
                        <th style="width: 130px;" class="py-3 text-nowrap">Total Harga</th>
                        <th style="width: 160px;" class="py-3 text-end px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $index => $sale)
                        <tr>
                            <td class="text-center px-3 text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-danger text-nowrap">{{ $sale->no_struk }}</td>
                            <td class="text-muted text-nowrap" style="font-size: 0.8rem;">
                                {{ $sale->created_at->format('d/m/Y') }}<br>
                                <small class="text-secondary">{{ $sale->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($sale->items as $item)
                                        <div class="d-flex justify-content-between align-items-center bg-light rounded px-2 py-1">
                                            <span style="font-size: 0.825rem;">
                                                • <strong>{{ $item->product->name ?? 'Produk Dihapus' }}</strong> 
                                                <span class="text-muted">({{ $item->quantity }} pcs)</span> 
                                                <span class="fw-semibold text-dark me-2">- Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            </span>
                                            
                                            <form action="{{ route('sales.item.cancel', $item->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Batalkan item ini dan kembalikan stok?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-warning btn-cancel-item">
                                                    Batal Item
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 11px;">
                                    {{ $sale->payment_method ?? 'Tunai' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success text-nowrap">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="text-end px-3">
                                <div class="d-flex gap-1 justify-content-end text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 0.8rem;" onclick="prepareReceiptModal('{{ $sale->no_struk }}', '{{ $sale->created_at->format('d/m/Y H:i') }}', {{ json_encode($sale->items) }}, {{ $sale->total }}, {{ $sale->bayar }}, {{ $sale->kembalian }}, '{{ $sale->payment_method ?? 'Tunai' }}')">
                                        <i class="bi bi-printer"></i> Struk
                                    </button>

                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan SELURUH transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 0.8rem;">
                                            <i class="bi bi-x-circle"></i> Batal Beli
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal Cetak Struk Kasir Thermal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 p-3 border-0 shadow-lg">
            <div id="printArea" class="receipt-container">
                <div class="text-center mb-1">
                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem; letter-spacing: 0.5px;">GLOWDESK SKINCARE</h6>
                    <small style="font-size: 0.7rem; display: block;">Beauty & Skincare POS System</small>
                    <small style="font-size: 0.65rem; display: block;">Jl. Merdeka No. 25, Lhokseumawe</small>
                </div>

                <div class="receipt-dashed-line"></div>

                <div style="font-size: 0.72rem;">
                    <div class="d-flex justify-content-between">
                        <span>No: <strong id="recNo">-</strong></span>
                        <span id="recDate">-</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kasir: <strong id="recCashier">{{ auth()->user()->name ?? 'Kasir' }}</strong></span>
                        <span>Pay: <strong id="recPayMethod">Tunai</strong></span>
                    </div>
                </div>

                <div class="receipt-dashed-line"></div>

                <div id="recItemsList" style="font-size: 0.72rem;" class="mb-1"></div>

                <div class="receipt-dashed-line"></div>

                <div style="font-size: 0.72rem;">
                    <div class="d-flex justify-content-between fw-bold my-1" style="font-size: 0.85rem;">
                        <span>TOTAL:</span>
                        <span id="recGrandTotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>BAYAR:</span>
                        <span id="recCash">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>KEMBALI:</span>
                        <span id="recChange">Rp 0</span>
                    </div>
                </div>

                <div class="receipt-dashed-line"></div>

                <div class="text-center mt-2" style="font-size: 0.65rem;">
                    <p class="mb-0 fw-bold">~ TERIMA KASIH ~</p>
                    <p class="mb-0 text-muted">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
                </div>
            </div>

            <div class="mt-3 no-print">
                <button type="button" class="btn btn-dark w-100 rounded-3 mb-2" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak Sekarang
                </button>
                <button type="button" class="btn btn-light w-100 rounded-3 text-muted" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart = [];
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const displayPrice = document.getElementById('displayPrice');
    const cartTableBody = document.getElementById('cartTableBody');
    const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
    const grandTotalText = document.getElementById('grandTotalText');
    const totalBelanjaInput = document.getElementById('totalBelanjaInput');
    const cashInput = document.getElementById('cashInput');
    const changeText = document.getElementById('changeText');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const paymentAlert = document.getElementById('paymentAlert');

    // Auto Add via Barcode Scanner Input
    document.getElementById('barcodeInput').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processBarcodeScan(this.value.trim());
        }
    });

    document.getElementById('btnScanAdd').addEventListener('click', function () {
        let input = document.getElementById('barcodeInput');
        processBarcodeScan(input.value.trim());
    });

    function processBarcodeScan(barcodeVal) {
        if (!barcodeVal) return;

        let options = productSelect.options;
        let foundIndex = -1;

        for (let i = 0; i < options.length; i++) {
            let barcodeAttr = options[i].getAttribute('data-barcode');
            let textName = options[i].text.toLowerCase();

            if (barcodeAttr && barcodeAttr.toLowerCase() === barcodeVal.toLowerCase()) {
                foundIndex = i;
                break;
            } else if (textName.includes(barcodeVal.toLowerCase())) {
                foundIndex = i;
                break;
            }
        }

        if (foundIndex > -1) {
            productSelect.selectedIndex = foundIndex;
            const price = parseFloat(options[foundIndex].getAttribute('data-price')) || 0;
            displayPrice.value = price.toLocaleString('id-ID');
            addToCart();
            document.getElementById('barcodeInput').value = '';
        } else {
            alert('Produk dengan Kode/SKU ' + barcodeVal + ' tidak ditemukan!');
        }
    }

    function filterProducts() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const options = productSelect.options;
        for (let i = 0; i < options.length; i++) {
            const text = options[i].text.toLowerCase();
            options[i].style.display = text.includes(input) ? '' : 'none';
        }
    }

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        displayPrice.value = price.toLocaleString('id-ID');
    });

    function addToCart() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (!selectedOption || productSelect.selectedIndex === -1) {
            alert('Pilih produk skincare terlebih dahulu!');
            return;
        }

        const productId = selectedOption.value;
        const productName = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const maxStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const qty = parseInt(quantityInput.value) || 0;

        if (qty <= 0 || qty > maxStock) {
            alert('Stok tidak cukup! Stok tersedia hanya ' + maxStock + ' pcs.');
            return;
        }

        const existingIndex = cart.findIndex(item => item.id === productId);
        if (existingIndex > -1) {
            if (cart[existingIndex].qty + qty > maxStock) {
                alert('Total belanjaan di keranjang melebihi stok yang ada!');
                return;
            }
            cart[existingIndex].qty += qty;
            cart[existingIndex].subtotal = cart[existingIndex].qty * price;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: price,
                qty: qty,
                subtotal: price * qty
            });
        }

        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        cartTableBody.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';
        let grandTotal = 0;

        if (cart.length === 0) {
            cartTableBody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted small">Keranjang kosong. Pilih produk di sebelah kiri.</td></tr>`;
        } else {
            cart.forEach((item, index) => {
                grandTotal += item.subtotal;
                
                cartTableBody.innerHTML += `
                    <tr>
                        <td class="fw-semibold">${item.name}</td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-end fw-bold">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeFromCart(${index})">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </td>
                    </tr>
                `;

                hiddenInputsContainer.innerHTML += `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                    <input type="hidden" name="items[${index}][price]" value="${item.price}">
                `;
            });
        }

        grandTotalText.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        totalBelanjaInput.value = grandTotal;

        calculateChange();
    }

    function calculateChange() {
        let grandTotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        let cash = parseFloat(cashInput.value) || 0;

        if (cart.length === 0) {
            checkoutBtn.disabled = true;
            paymentAlert.style.display = 'none';
            changeText.innerText = 'Rp 0';
            return;
        }

        if (cash < grandTotal) {
            checkoutBtn.disabled = true;
            paymentAlert.style.display = 'block';
            changeText.innerText = 'Rp 0';
        } else {
            checkoutBtn.disabled = false;
            paymentAlert.style.display = 'none';
            let change = cash - grandTotal;
            changeText.innerText = 'Rp ' + change.toLocaleString('id-ID');
        }
    }

    function validateCheckout() {
        let grandTotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        let cash = parseFloat(cashInput.value) || 0;

        if (cart.length === 0) {
            alert('Keranjang masih kosong!');
            return false;
        }
        if (cash < grandTotal) {
            alert('Uang pembayaran tidak cukup!');
            return false;
        }
        return true;
    }

    function prepareReceiptModal(no, date, items, total, bayar, kembalian, method) {
        document.getElementById('recNo').innerText = no;
        document.getElementById('recDate').innerText = date;
        document.getElementById('recCashier').innerText = "{{ auth()->user()->name ?? 'Kasir' }}";
        document.getElementById('recPayMethod').innerText = method || 'Tunai';
        
        let itemsHtml = '';
        items.forEach(item => {
            let pName = item.product ? item.product.name : 'Produk Dihapus';
            itemsHtml += `
                <div class="mb-1">
                    <div class="fw-bold text-uppercase">${pName}</div>
                    <div class="d-flex justify-content-between">
                        <span>${item.quantity} x Rp ${parseFloat(item.unit_price).toLocaleString('id-ID')}</span>
                        <span class="fw-bold">Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        });
        
        document.getElementById('recItemsList').innerHTML = itemsHtml;
        document.getElementById('recGrandTotal').innerText = 'Rp ' + parseFloat(total).toLocaleString('id-ID');
        document.getElementById('recCash').innerText = 'Rp ' + parseFloat(bayar || total).toLocaleString('id-ID');
        document.getElementById('recChange').innerText = 'Rp ' + parseFloat(kembalian || 0).toLocaleString('id-ID');

        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
    }
</script>
</body>
</html>