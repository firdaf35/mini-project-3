<?php
// views/header.php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/escape.php';

requireLogin(); // Memastikan user harus login terlebih dahulu
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom & Co. - Florist Inventory System</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { margin: 0; padding: 0; background-color: #faf5f5; color: #4a4a4a; }
        
        /* Navbar Styling (Tema Florist Pink Pastel) */
        .navbar { background-color: #f7d6e0; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .navbar .brand { font-size: 20px; font-weight: bold; color: #844685; text-decoration: none; }
        .nav-links { display: flex; gap: 20px; list-style: none; margin: 0; padding: 0; }
        .nav-links a { text-decoration: none; color: #5a3d5c; font-weight: 600; padding: 6px 12px; border-radius: 4px; transition: 0.2s; }
        .nav-links a:hover { background-color: #f2bac9; color: #2c132e; }
        
        .user-info { font-size: 14px; color: #6b4d6d; display: flex; align-items: center; gap: 15px; }
        .btn-logout { background-color: #e09fba; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-logout:hover { background-color: #c97d9e; }
        
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #f3e5e8; margin-bottom: 20px; }
        
        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #f0e6e8; }
        th { background-color: #fbf0f3; color: #6b3e6e; font-weight: 600; }
        
        /* Form & Button Elements */
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; border: none; text-decoration: none; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primary { background-color: #b5838d; color: white; }
        .btn-primary:hover { background-color: #9f6b75; }
        .btn-success { background-color: #a8d5e2; color: #21434e; }
        .btn-danger { background-color: #f4a261; color: white; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-admin { background-color: #d8e2dc; color: #3d5a80; }
        .badge-staff { background-color: #ffe5ec; color: #9d8189; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/inventory-app/public/dashboard.php" class="brand">🌸 Bloom & Co. Florist</a>
    <ul class="nav-links">
        <li><a href="/inventory-app/public/dashboard.php">Dashboard</a></li>
        <li><a href="/inventory-app/public/products/index.php">Data Bunga/Produk</a></li>
        <li><a href="/inventory-app/public/stock/in.php">Barang Masuk</a></li>
        <li><a href="/inventory-app/public/stock/out.php">Barang Keluar</a></li>
        <li><a href="/inventory-app/public/stock/history.php">Histori Stok</a></li>
    </ul>
    <div class="user-info">
        <span>Halo, <strong><?= e($_SESSION['username']); ?></strong> 
            <span class="badge badge-<?= e($_SESSION['role']); ?>"><?= strtoupper(e($_SESSION['role'])); ?></span>
        </span>
        <a href="/inventory-app/public/logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="container">