<?php
// public/stock/history.php
session_start();
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$history = $pdo->query("
    SELECT st.*, p.name AS product_name, u.username 
    FROM stock_transactions st
    JOIN products p ON st.product_id = p.id
    JOIN users u ON st.user_id = u.id
    ORDER BY st.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Stok - Bloom & Co. Florist</title>
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
                <a href="out.php" class="hover:underline">Barang Keluar</a>
                <a href="history.php" class="hover:underline font-semibold underline">Histori Stok</a>
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
            <h2 class="text-xl font-bold text-slate-800 mb-4">📋 Histori Transaksi Stok Bunga</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-600 text-sm">
                            <th class="py-3 px-4">Waktu Transaksi</th>
                            <th class="py-3 px-4">Nama Produk</th>
                            <th class="py-3 px-4">Tipe Transaksi</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Belum ada riwayat transaksi stok.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($history as $h): ?>
                                <tr>
                                    <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($h['created_at']) ?></td>
                                    <td class="py-3 px-4 font-medium text-slate-800"><?= htmlspecialchars($h['product_name']) ?></td>
                                    <td class="py-3 px-4">
                                        <?php if (strtoupper($h['type']) === 'IN'): ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Masuk (+)</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Keluar (-)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-800"><?= htmlspecialchars($h['quantity']) ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($h['username']) ?></td>
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