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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    $quantity  = intval($_POST['quantity'] ?? 0);
    $notes     = trim($_POST['notes'] ?? '');

    if (!$productId) {
        $errors[] = 'Pilih produk yang valid.';
    }
    if ($quantity < 1) {
        $errors[] = 'Jumlah barang masuk harus minimal 1.';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $stmtInsert = $pdo->prepare('INSERT INTO stock_movements (product_id, user_id, type, quantity, notes) VALUES (:product_id, :user_id, "IN", :quantity, :notes)');
            $stmtInsert->execute([
                'product_id' => $productId,
                'user_id'    => $_SESSION['user']['id'],
                'quantity'   => $quantity,
                'notes'      => $notes
            ]);

            $stmtUpdate = $pdo->prepare('UPDATE products SET stock = stock + :qty WHERE id = :id');
            $stmtUpdate->execute(['qty' => $quantity, 'id' => $productId]);

            $pdo->commit();

            header('Location: /catalog');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}

$products = $pdo->query('SELECT id, name, sku, stock FROM products ORDER BY name ASC')->fetchAll();
$totalMasukBulanIni = $pdo->query('SELECT SUM(quantity) FROM stock_movements WHERE type="IN"')->fetchColumn() ?? 0;
$totalNilai = $pdo->query("SELECT SUM(price * stock) FROM products")->fetchColumn() ?? 0;
$totalProducts = count($products);
$totalStock = $pdo->query("SELECT SUM(stock) FROM products")->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MahzaGlow - Barang Masuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; }
        body { background: #0f172a; padding: 32px 24px; color: #0f172a; min-height: 100vh; height: auto; display: flex; justify-content: center; align-items: flex-start; }
        .app-board { width: 100%; max-width: 1440px; background: #f8fafc; border-radius: 32px; padding: 32px; margin-bottom: 40px; min-height: 860px; }
        .board-grid-sidebar { display: grid; grid-template-columns: 250px 1fr 340px; gap: 24px; align-items: start; }
        
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
        
        .main-content-area { display: flex; flex-direction: column; gap: 20px; }
        .hero-banner { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0072ff 100%); border-radius: 24px; padding: 28px; color: white; display: flex; flex-direction: column; justify-content: space-between; }
        .soft-card { background: #ffffff; border-radius: 24px; padding: 28px; border: 1px solid #f1f5f9; }
        
        input, select, textarea { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 14px; font-size: 13px; background: #ffffff; outline: none; margin-top: 6px; margin-bottom: 14px; }
        label { font-size: 11px; font-weight: 800; color: #475569; display: block; text-transform: uppercase; }
        .btn-primary-blue { padding: 13px; background: #0072ff; border: none; border-radius: 14px; color: #ffffff; font-weight: 800; font-size: 13px; cursor: pointer; text-align: center; width: 100%; }

        .right-panel { display: flex; flex-direction: column; gap: 18px; }
        .summary-card-top { background: #ffffff; border-radius: 20px; padding: 20px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .glowing-blue-card { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #10b981 100%); border-radius: 20px; padding: 22px; color: white; }
    </style>
</head>
<body>

<div class="app-board">
    <div class="board-grid-sidebar">
        
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
                    <a href="/dashboard" class="nav-pill-side">📊 Dashboard</a>
                    <a href="/catalog" class="nav-pill-side">🧴 Katalog Skincare</a>
                    <a href="/stock/in" class="nav-pill-side active">📥 Barang Masuk</a>
                    <a href="/stock/out" class="nav-pill-side">📤 Barang Keluar</a>
                </nav>

                <div class="sidebar-promo-card">
                    <span style="font-size: 22px;">📦</span>
                    <h5 style="font-size: 12px; font-weight: 800; color: #38bdf8; margin: 6px 0 2px 0;">Restock Supplier</h5>
                    <p style="font-size: 10px; color: #94a3b8; font-weight: 600;">Update Stok Otomatis</p>
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

        <div class="main-content-area">
            <div class="hero-banner">
                <div>
                    <span style="font-size: 10px; font-weight: 800; opacity: 0.8; text-transform: uppercase;">TOTAL NILAI INVENTARIS</span>
                    <h2 style="font-size: 28px; font-weight: 800; margin: 6px 0;">Rp <?= number_format($totalNilai, 0, ',', '.') ?></h2>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 11px; opacity: 0.85;">
                    <span>Modul Restock Aktif:</span>
                    <span style="background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 8px; font-weight: 700;">📥 Barang Masuk (IN)</span>
                </div>
            </div>

            <div class="soft-card">
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 16px;">📥 Formulir Pencatatan Skincare Masuk</h3>

                <?php if (!empty($errors)): ?>
                    <div style="background:#fef2f2; color:#ef4444; padding:12px; border-radius:14px; margin-bottom:20px; font-size:12px;">
                        <?php foreach ($errors as $err): ?>
                            <p>⚠️ <?= htmlspecialchars($err) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    @csrf
                    <div>
                        <label>Pilihan Produk Skincare</label>
                        <select name="product_id" required>
                            <option value="">-- Pilih Produk Skincare --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (SKU: <?= htmlspecialchars($p['sku']) ?> | Stok: <?= $p['stock'] ?> unit)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label>Jumlah Unit Masuk</label>
                        <input type="number" name="quantity" placeholder="Contoh: 20" min="1" required>
                    </div>

                    <div>
                        <label>Catatan / Supplier / No. Faktur</label>
                        <textarea name="notes" rows="3" placeholder="Contoh: Restock Supplier PT Skincare Indah Batch #99"></textarea>
                    </div>

                    <button type="submit" class="btn-primary-blue">Simpan Transaksi Masuk</button>
                </form>
            </div>
        </div>

        <div class="right-panel">
            <div class="summary-card-top">
                <div>
                    <span style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase;">RINGKASAN INVENTARIS</span>
                    <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 4px;"><?= $totalProducts ?> <span style="font-size: 11px; color: #64748b;">Jenis</span></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 20px; font-weight: 800; color: #0072ff;"><?= $totalStock ?></div>
                    <div style="font-size: 11px; color: #64748b; font-weight: 700;">Total Unit</div>
                </div>
            </div>

            <div class="glowing-blue-card">
                <span style="font-size: 10px; font-weight: 800; opacity: 0.8; text-transform: uppercase;">TOTAL RESTOCK MASUK</span>
                <h3 style="font-size: 26px; font-weight: 800; margin: 6px 0;"><?= $totalMasukBulanIni ?> Unit</h3>
                <p style="font-size: 11px; opacity: 0.85;">Total penambahan unit stok masuk</p>
            </div>

            <div class="soft-card" style="border-left: 4px solid #10b981;">
                <span style="font-size: 11px; color: #10b981; font-weight: 800; text-transform: uppercase;">💡 Info Restock</span>
                <p style="font-size: 12px; color: #475569; margin-top: 8px; line-height: 1.5; font-weight: 600;">Jumlah barang masuk langsung ditambahkan ke stok aktif produk secara real-time.</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>