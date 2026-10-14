<?php
// public/products/edit.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$error = '';

// Ambil data produk berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

// Ambil daftar kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $price = floatval($_POST['price'] ?? 0);

    if (!empty($sku) && !empty($name) && !empty($category_id)) {
        try {
            $stmtUpdate = $pdo->prepare("UPDATE products SET sku = ?, name = ?, category_id = ?, price = ? WHERE id = ?");
            $stmtUpdate->execute([$sku, $name, $category_id, $price, $id]);

            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $error = "Gagal memperbarui produk: " . $e->getMessage();
        }
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Bloom & Co. Florist</title>
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
                <h2 class="text-xl font-bold text-slate-800">✏️ Edit Bunga / Produk</h2>
                <a href="index.php" class="text-sm text-purple-600 hover:underline">&larr; Kembali</a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode SKU</label>
                    <input type="text" name="sku" required value="<?= htmlspecialchars($product['sku']) ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Bunga / Produk</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($product['name']) ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" step="100" min="0" required value="<?= htmlspecialchars($product['price']) ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                    Perbarui Produk
                </button>
            </form>
        </div>
    </main>
</body>
</html>