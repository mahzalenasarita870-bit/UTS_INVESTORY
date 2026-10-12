<?php
use Illuminate\Support\Facades\DB;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$pdo = DB::connection()->getPdo();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'quick_add') {
    $name       = trim($_POST['name'] ?? '');
    $categoryId = intval($_POST['category_id'] ?? 0);
    $price      = floatval($_POST['price'] ?? 0);
    $stock      = intval($_POST['stock'] ?? 0);
    $sku        = 'SKU-' . strtoupper(substr(md5(uniqid()), 0, 6));

    if (empty($name) || !$categoryId || $price <= 0) {
        $errors[] = 'Nama, Kategori, dan Harga wajib diisi dengan benar.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO products (name, category_id, price, stock, sku) VALUES (:name, :category_id, :price, :stock, :sku)');
            $stmt->execute([
                'name'        => $name,
                'category_id' => $categoryId,
                'price'       => $price,
                'stock'       => $stock,
                'sku'         => $sku
            ]);
            header('Location: /dashboard');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}

$categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
if ($categoryId > 0) {
    $query .= " WHERE p.category_id = " . $categoryId;
}
$query .= " ORDER BY p.id DESC";

$products = $pdo->query($query)->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$totalProducts = count($pdo->query("SELECT id FROM products")->fetchAll());
$totalStock = $pdo->query("SELECT SUM(stock) FROM products")->fetchColumn() ?? 0;
$totalNilai = $pdo->query("SELECT SUM(price * stock) FROM products")->fetchColumn() ?? 0;
$lowStockCount = $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= 5")->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MahzaGlow - Dashboard & Katalog</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; }
        body { background: #0f172a; padding: 32px 24px; color: #0f172a; min-height: 100vh; height: auto; display: flex; justify-content: center; align-items: flex-start; }
        .app-board { width: 100%; max-width: 1440px; background: #f8fafc; border-radius: 32px; padding: 32px; margin-bottom: 40px; }
        .board-grid-sidebar { display: grid; grid-template-columns: 250px 1fr 340px; gap: 24px; align-items: start; }
        
        /* Sidebar Kiri */
        .sidebar-left { background: #ffffff; border-radius: 24px; padding: 24px 18px; border: 1px solid #f1f5f9; display: flex; flex-direction: column; justify-content: space-between; min-height: 800px; }
        .brand-wrapper { display: flex; align-items: center; gap: 12px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0; margin-bottom: 24px; }
        .brand-icon-glow { width: 44px; height: 44px; background: #0072ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px; }
        .brand-title-text { font-size: 19px; font-weight: 800; color: #0f172a; }
        .brand-subtitle { font-size: 10px; color: #64748b; font-weight: 700; }
        .side-nav-menu { display: flex; flex-direction: column; gap: 8px; }
        .nav-pill-side { padding: 12px 16px; border-radius: 16px; font-size: 13px; font-weight: 700; color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.2s; }
        .nav-pill-side.active { background: #0072ff; color: #ffffff; box-shadow: 0 10px 15px -3px rgba(0, 114, 255, 0.3); }
        .sidebar-promo-card { background: #0f172a; border-radius: 20px; padding: 18px; margin-top: 28px; color: white; }
        .user-profile-badge-side { display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 12px; border-radius: 20px; }
        .user-avatar-circle { width: 38px; height: 38px; background: #0072ff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; }
        
        /* Area Tengah Katalog & Banner */
        .main-catalog-area { display: flex; flex-direction: column; gap: 20px; }
        .hero-banner { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0072ff 100%); border-radius: 24px; padding: 28px; color: white; display: flex; flex-direction: column; justify-content: space-between; }
        
        .category-bar { display: flex; gap: 8px; flex-wrap: wrap; }
        .cat-chip { padding: 9px 16px; border-radius: 14px; font-size: 12px; font-weight: 700; background: #ffffff; color: #64748b; text-decoration: none; border: 1px solid #e2e8f0; transition: 0.2s; }
        .cat-chip.active { background: #0072ff; color: #ffffff; border-color: #0072ff; }
        
        .products-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .product-card { background: #ffffff; border-radius: 20px; padding: 20px; border: 1px solid #f1f5f9; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .card-header-tag { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .tag-category { background: #e0f2fe; color: #0284c7; font-size: 10px; font-weight: 800; padding: 6px 12px; border-radius: 10px; text-transform: uppercase; }
        .tag-stock { font-size: 11px; font-weight: 700; color: #10b981; display: flex; align-items: center; gap: 4px; }
        .product-title { font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .product-price { font-size: 18px; font-weight: 800; color: #0072ff; margin-bottom: 16px; }
        .card-actions { display: flex; gap: 8px; }
        .btn-action-edit { flex: 1; padding: 10px; background: #f1f5f9; color: #334155; border-radius: 12px; text-align: center; font-weight: 700; font-size: 12px; text-decoration: none; }
        .btn-action-delete { flex: 1; padding: 10px; background: #fef2f2; color: #ef4444; border-radius: 12px; text-align: center; font-weight: 700; font-size: 12px; text-decoration: none; }

        /* Panel Kanan */
        .right-panel { display: flex; flex-direction: column; gap: 18px; }
        .summary-card-top { background: #ffffff; border-radius: 20px; padding: 20px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .warning-card { background: #ffffff; border-radius: 20px; padding: 20px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #f59e0b; }
        .form-quick-card { background: #ffffff; border-radius: 24px; padding: 22px; border: 1px solid #f1f5f9; }
        input, select { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 14px; font-size: 13px; background: #ffffff; outline: none; margin-top: 6px; margin-bottom: 14px; }
        label { font-size: 11px; font-weight: 800; color: #475569; display: block; text-transform: uppercase; }
        .btn-primary-blue { padding: 13px; background: #0072ff; border: none; border-radius: 14px; color: #ffffff; font-weight: 800; font-size: 13px; cursor: pointer; text-align: center; width: 100%; }
    </style>
</head>
<body>

<div class="app-board">
    <div class="board-grid-sidebar">
        
        <!-- Sidebar Kiri -->
        <div class="sidebar-left">
            <div>
                <div class="brand-wrapper">
                    <div class="brand-icon-glow">M</div>
                    <div>
                        <span class="brand-title-text">MahzaGlow</span>
                        <div class="brand-subtitle">Skincare Inventory System</div>
                    </div>
                </div>

                <nav class="side-nav-menu">
                    <a href="/dashboard" class="nav-pill-side active">📊 Dashboard</a>
                    <a href="/catalog" class="nav-pill-side">🧴 Katalog Skincare</a>
                    <a href="/stock/in" class="nav-pill-side">📥 Barang Masuk</a>
                    <a href="/stock/out" class="nav-pill-side">📤 Barang Keluar</a>
                </nav>

                <div class="sidebar-promo-card">
                    <span style="font-size: 22px;">✨</span>
                    <h5 style="font-size: 12px; font-weight: 800; color: #38bdf8; margin: 6px 0 2px 0;">Live Inventory System</h5>
                    <p style="font-size: 10px; color: #94a3b8; font-weight: 600;">Otomatisasi Stok Terpusat</p>
                </div>
            </div>

            <div class="user-profile-badge-side">
                <div class="user-avatar-circle"><?= strtoupper(substr($_SESSION['user']['username'] ?? 'A', 0, 1)) ?></div>
                <div style="flex:1;">
                    <p style="font-size:12px; font-weight:800; color:#0f172a; margin:0;"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?></p>
                    <span style="font-size:9px; color:#64748b; font-weight:700; text-transform:uppercase;"><?= htmlspecialchars($_SESSION['user']['role'] ?? 'ADMIN') ?></span>
                </div>
                <a href="/logout" style="font-size:11px; color:#ef4444; font-weight:800; text-decoration:none;">Logout</a>
            </div>
        </div>

        <!-- Area Tengah (Banner, Kategori, & Grid Produk Katalog) -->
        <div class="main-catalog-area">
            
            <div class="hero-banner">
                <div>
                    <span style="font-size: 10px; font-weight: 800; opacity: 0.8; text-transform: uppercase;">TOTAL NILAI INVENTARIS</span>
                    <h2 style="font-size: 28px; font-weight: 800; margin: 6px 0;">Rp <?= number_format($totalNilai, 0, ',', '.') ?></h2>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 11px; opacity: 0.85;">
                    <span>Sistem Sinkronisasi:</span>
                    <span style="background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 8px; font-weight: 700;">🟢 Real-time Engine</span>
                </div>
            </div>

            <div class="category-bar">
                <a href="/dashboard" class="cat-chip <?= $categoryId === 0 ? 'active' : '' ?>">Semua Kategori</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/dashboard?category_id=<?= $cat['id'] ?>" class="cat-chip <?= $categoryId === (int)$cat['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <div style="grid-column: 1 / -1; background: #ffffff; padding: 40px; text-align: center; border-radius: 20px; color: #64748b; font-weight: 700;">
                        Belum ada produk skincare dalam kategori ini.
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <?php 
                            $priceVal = (float)$p['price'];
                            if ($priceVal < 1000 && $priceVal > 0) { $priceVal *= 1000; }
                            $formattedPrice = "Rp " . number_format($priceVal, 0, ',', '.');
                        ?>
                        <div class="product-card">
                            <div>
                                <div class="card-header-tag">
                                    <span class="tag-category"><?= htmlspecialchars($p['category_name'] ?? 'SKINCARE') ?></span>
                                    <span class="tag-stock">🟢 <?= $p['stock'] ?> unit</span>
                                </div>
                                <h3 class="product-title"><?= htmlspecialchars($p['name']) ?></h3>
                                <div class="product-price"><?= $formattedPrice ?></div>
                            </div>
                            
                            <div class="card-actions">
                                <a href="/catalog/edit?id=<?= $p['id'] ?>" class="btn-action-edit">Edit</a>
                                <a href="/catalog/delete?id=<?= $p['id'] ?>" class="btn-action-delete" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

        <!-- Panel Kanan -->
        <div class="right-panel">
            
            <div class="summary-card-top">
                <div>
                    <span style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase;">RINGKASAN INVENTARIS</span>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?= $totalProducts ?> <span style="font-size: 11px; color: #64748b;">Jenis Skincare</span></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 18px; font-weight: 800; color: #0072ff;"><?= $totalStock ?></div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700;">Total Unit Stok</div>
                </div>
            </div>

            <div class="warning-card">
                <div>
                    <span style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase;">STOK MENIPIS</span>
                    <div style="font-size: 15px; font-weight: 800; color: #ef4444; margin-top: 2px;"><?= $lowStockCount ?> Produk</div>
                </div>
                <span style="font-size: 20px;">⚠️</span>
            </div>

            <div class="form-quick-card">
                <h4 style="font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">+ Tambah Skincare Quick</h4>
                
                <?php if (!empty($errors)): ?>
                    <div style="background:#fef2f2; color:#ef4444; padding:8px; border-radius:10px; font-size:11px; margin-bottom:10px;">
                        <?= htmlspecialchars($errors[0]) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    @csrf
                    <input type="hidden" name="action" value="quick_add">
                    
                    <div>
                        <label>Nama Produk</label>
                        <input type="text" name="name" placeholder="Nama Produk Skincare" required>
                    </div>

                    <div>
                        <label>Kategori</label>
                        <select name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" placeholder="85000" required>
                    </div>

                    <div>
                        <label>Jumlah Stok</label>
                        <input type="number" name="stock" placeholder="0" value="0" required>
                    </div>

                    <button type="submit" class="btn-primary-blue">Simpan Skincare</button>
                </form>
            </div>

        </div>

    </div>
</div>

</body>
</html>