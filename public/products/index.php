<?php
// public/products/index.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil data produk beserta nama kategorinya
$products = $pdo->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Bunga/Produk - Bloom & Co. Florist</title>
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
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">🌸 Data Bunga & Produk Florist</h2>
                <a href="create.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    + Tambah Produk Baru
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-600 text-sm">
                            <th class="py-3 px-4">SKU</th>
                            <th class="py-3 px-4">Nama Produk</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Harga</th>
                            <th class="py-3 px-4">Stok</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="py-4 text-center text-slate-400">Belum ada data produk bunga.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="py-3 px-4 font-mono text-xs text-purple-700 font-semibold"><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                                    <td class="py-3 px-4 font-medium text-slate-800"><?= htmlspecialchars($p['name']) ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($p['category_name'] ?? 'Tanpa Kategori') ?></td>
                                    <td class="py-3 px-4 text-slate-600">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td class="py-3 px-4 font-bold text-slate-800"><?= htmlspecialchars($p['stock']) ?></td>
                                    <td class="py-3 px-4 text-center space-x-2">
                                        <a href="edit.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline font-medium">Edit</a>
                                        <a href="delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="text-red-600 hover:underline font-medium">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>