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
$productId = intval($_GET['id'] ?? 0);

if ($productId) {
    try {
        $stmtDelete = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmtDelete->execute(['id' => $productId]);
    } catch (Exception $e) {
        // Abaikan jika ada keterikatan relasi
    }
}

header('Location: /catalog');
exit;