<?php
// public/products/create.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Ambil daftar kategori untuk dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    if (!empty($sku) && !empty($name) && !empty($category_id)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (sku, name, category_id, price, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$sku, $name, $category_id, $price, $stock]);
            
            // Catat juga ke transaksi awal jika stok > 0
            if ($stock > 0) {
                $product_id = $pdo->lastInsertId();
                $stmtTrx = $pdo->prepare("INSERT INTO stock_transactions (product_id, user_id, type, quantity) VALUES (?, ?, 'IN', ?)");
                $stmtTrx->execute([$product_id, $_SESSION['user_id'], $stock]);
            }

            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $error = "Gagal menyimpan produk (Kemungkinan SKU sudah terdaftar): " . $e->getMessage();
        }
    } else {
        $error = "SKU, Nama Produk, dan Kategori wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Bloom & Co. Florist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-purple-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">Bloom & Co. Florist</h1>
            <div class="flex items-center gap-4">
                <a href="../dashboard.php" class="hover:underline">Dashboard</a>
                <a href="index.php" class="hover:underline font-semibold underline">Data Bunga/Produk</a>
                <a href="../stock/in.php" class="hover:underline">Barang Masuk</a>
                <a href="../stock/out.php" class="hover:underline">Barang Keluar</a>
                <a href="../stock/history.php" class="hover:underline">Histori Stok</a>
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">➕ Tambah Bunga / Produk Baru</h2>
                <a href="index.php" class="text-sm text-purple-600 hover:underline">&larr; Kembali</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode SKU (Harus Unik)</label>
                    <input type="text" name="sku" required placeholder="Contoh: FLW-MWR-003" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Bunga / Produk</label>
                    <input type="text" name="name" required placeholder="Contoh: Mawar Merah Special" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" step="100" min="0" required placeholder="15000" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Stok Awal</label>
                    <input type="number" name="stock" min="0" required value="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                    Simpan Produk Baru
                </button>
            </form>
        </div>
    </main>
</body>
</html>