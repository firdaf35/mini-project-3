<?php
// public/dashboard.php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'staff';

// Hitung total produk & stok
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalStock = $pdo->query("SELECT SUM(stock) FROM products")->fetchColumn() ?: 0;
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Ambil 5 transaksi stok terbaru
$recentTransactions = $pdo->query("
    SELECT st.*, p.name AS product_name, u.username 
    FROM stock_transactions st
    JOIN products p ON st.product_id = p.id
    JOIN users u ON st.user_id = u.id
    ORDER BY st.created_at DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bloom & Co. Florist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <!-- Navbar / Header -->
    <nav class="bg-purple-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">Bloom & Co. Florist</h1>
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="hover:underline">Dashboard</a>
                <a href="products/index.php" class="hover:underline">Data Bunga/Produk</a>
                <a href="stock/in.php" class="hover:underline">Barang Masuk</a>
                <a href="stock/out.php" class="hover:underline">Barang Keluar</a>
                <a href="stock/history.php" class="hover:underline">Histori Stok</a>
                <span class="text-sm bg-purple-800 px-3 py-1 rounded-full">
                    Halo, <strong><?= htmlspecialchars($username) ?></strong> (<?= strtoupper($role) ?>)
                </span>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Dashboard Inventaris Florist</h2>

        <!-- Cards Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-500 text-sm">Total Item Bunga</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2"><?= $totalProducts ?></p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-500 text-sm">Total Stok Tangkai/Unit</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2"><?= $totalStock ?></p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-semibold text-slate-500 text-sm">Kategori Florist</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2"><?= $totalCategories ?></p>
            </div>
        </div>

        <!-- Tabel 5 Transaksi Terbaru -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">📦 5 Transaksi Stok Terbaru</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-600 text-sm">
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Bunga/Produk</th>
                            <th class="py-3 px-4">Tipe</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($recentTransactions)): ?>
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Belum ada transaksi stok.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentTransactions as $trx): ?>
                                <tr>
                                    <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($trx['created_at']) ?></td>
                                    <td class="py-3 px-4 font-medium text-slate-800"><?= htmlspecialchars($trx['product_name']) ?></td>
                                    <td class="py-3 px-4">
                                        <?php if (strtoupper($trx['type']) === 'IN'): ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Masuk (+)</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Keluar (-)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-800"><?= htmlspecialchars($trx['quantity']) ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($trx['username']) ?></td>
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