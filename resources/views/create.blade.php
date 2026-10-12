<?php
use Illuminate\Support\Facades\DB;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /dashboard');
    exit;
}

$pdo = DB::connection()->getPdo();

$errors       = [];
$sku          = '';
$name         = '';
$categoryId   = '';
$price        = '';
$stock        = '0';
$minimumStock = '5';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku          = trim($_POST['sku'] ?? '');
    $name         = trim($_POST['name'] ?? '');
    $categoryId   = intval($_POST['category_id'] ?? 0);
    $price        = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $stock        = intval($_POST['stock'] ?? 0);
    $minimumStock = intval($_POST['minimum_stock'] ?? 5);

    if (empty($sku)) {
        $errors[] = 'SKU wajib diisi.';
    } else {
        $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM products WHERE sku = :sku');
        $stmtCheck->execute(['sku' => $sku]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors[] = 'SKU sudah terdaftar.';
        }
    }

    if (empty($name)) {
        $errors[] = 'Nama produk wajib diisi.';
    }

    if (!$categoryId) {
        $errors[] = 'Pilih kategori yang valid.';
    }

    if ($price === false || $price < 0) {
        $errors[] = 'Harga produk tidak valid.';
    }

    if (empty($errors)) {
        $stmtInsert = $pdo->prepare('
            INSERT INTO products (sku, name, category_id, price, stock, minimum_stock) 
            VALUES (:sku, :name, :cat_id, :price, :stock, :min_stock)
        ');
        $stmtInsert->execute([
            'sku'       => $sku,
            'name'      => $name,
            'cat_id'    => $categoryId,
            'price'     => $price,
            'stock'     => $stock,
            'min_stock' => $minimumStock
        ]);

        header('Location: /catalog');
        exit;
    }
}

$categoriesList = $pdo->query('SELECT id, name FROM categories ORDER BY name ASC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MahzaGlow - Tambah Produk Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; }
        body { background: #0f172a; padding: 24px; color: #0f172a; min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; }
        .app-board { width: 100%; max-width: 800px; background: #f8fafc; border-radius: 32px; padding: 32px; }
        input, select { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 14px; font-size: 13px; background: #ffffff; outline: none; margin-top: 6px; margin-bottom: 14px; }
        label { font-size: 11px; font-weight: 800; color: #475569; display: block; text-transform: uppercase; }
        .btn-primary-blue { width: 100%; padding: 13px; background: #0072ff; border: none; border-radius: 14px; color: #ffffff; font-weight: 800; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
        .btn-secondary { padding: 13px; background: #e2e8f0; border-radius: 14px; color: #334155; font-weight: 800; font-size: 13px; text-decoration: none; display: inline-block; text-align: center; }
    </style>
</head>
<body>

<div class="app-board">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:20px; font-weight:800; color:#0f172a;">Tambah Produk Skincare Baru</h2>
        <a href="/catalog" class="btn-secondary">← Kembali ke Katalog</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div style="background:#fef2f2; border:1px solid #fee2e2; color:#ef4444; padding:12px; border-radius:14px; margin-bottom:20px; font-size:12px;">
            <?php foreach ($errors as $err): ?>
                <p style="margin:2px 0;">⚠️ <?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        @csrf
        <label>SKU (Kode Produk)</label>
        <input type="text" name="sku" value="<?= htmlspecialchars($sku) ?>" placeholder="Contoh: FW-001" required>

        <label>Nama Produk Skincare</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Contoh: Gentle Facial Wash 100ml" required>

        <label>Kategori</label>
        <select name="category_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categoriesList as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Harga (Rp)</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" placeholder="85000" required>

        <label>Stok Awal</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" min="0" required>

        <label>Batas Stok Minimal</label>
        <input type="number" name="minimum_stock" value="<?= htmlspecialchars($minimumStock) ?>" min="0" required>

        <div style="display:flex; gap:10px; margin-top:10px;">
            <button type="submit" class="btn-primary-blue" style="flex:2;">Simpan Produk</button>
            <a href="/catalog" class="btn-secondary" style="flex:1;">Batal</a>
        </div>
    </form>
</div>

</body>
</html>