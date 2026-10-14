<?php
// public/products/delete.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/auth.php';

requireAdmin(); // Hanya Admin yang bisa hapus data

$id = $_GET['id'] ?? 0;

if ($id) {
    // Cek apakah produk sudah punya histori transaksi
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM stock_transactions WHERE product_id = ?");
    $stmtCheck->execute([$id]);

    if ($stmtCheck->fetchColumn() > 0) {
        http_response_code(400);
        die("<h3>Akses Ditolak / Error Validation:</h3> <p>Produk ini tidak boleh dihapus karena memiliki histori transaksi pergerakan stok!</p><a href='index.php'>Kembali</a>");
    } else {
        $stmtDel = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmtDel->execute([$id]);
    }
}

header('Location: index.php');
exit;