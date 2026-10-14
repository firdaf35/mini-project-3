<?php
// public/stock/out.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Ambil daftar produk beserta stok saat ini
$products = $pdo->query("SELECT id, name, sku, stock FROM products ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $user_id = $_SESSION['user_id'];

    if (!empty($product_id) && $quantity > 0) {
        // Cek stok apakah mencukupi
        $stmtProd = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmtProd->execute([$product_id]);
        $currentStock = $stmtProd->fetchColumn();

        if ($currentStock >= $quantity) {
            try {
                $pdo->beginTransaction();

                // Catat transaksi stok keluar (Gunakan huruf besar 'OUT')
                $stmt = $pdo->prepare("INSERT INTO stock_transactions (product_id, user_id, type, quantity) VALUES (?, ?, 'OUT', ?)");
                $stmt->execute([$product_id, $user_id, $quantity]);

                // Kurangi stok produk
                $update = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $update->execute([$quantity, $product_id]);

                $pdo->commit();
                $success = "Stok keluar berhasil dicatat!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
        } else {
            $error = "Stok tidak mencukupi! Sisa stok saat ini: " . $currentStock;
        }
    } else {
        $error = "Pilih produk dan masukkan jumlah keluar yang valid!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar - Bloom & Co. Florist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-purple-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">Bloom & Co. Florist</h1>
            <div class="flex items-center gap-4">
                <a href="../dashboard.php" class="hover:underline">Dashboard</a>
                <a href="../products/index.php" class="hover:underline">Data Bunga/Produk</a>
                <a href="in.php" class="hover:underline">Barang Masuk</a>
                <a href="out.php" class="hover:underline font-semibold underline">Barang Keluar</a>
                <a href="history.php" class="hover:underline">Histori Stok</a>
                <span class="text-sm bg-purple-800 px-3 py-1 rounded-full">
                    Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </span>
                <a href="../logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-xl mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 mb-4">📤 Catat Barang Keluar (Stok Keluar)</h2>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg"><?= $error ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-600 text-sm rounded-lg"><?= $success ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Produk Bunga</label>
                    <select name="product_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['name']) ?> (Sisa Stok: <?= $p['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Keluar (Tangkai/Unit)</label>
                    <input type="number" name="quantity" min="1" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                    Simpan Stok Keluar
                </button>
            </form>
        </div>
    </main>
</body>
</html>