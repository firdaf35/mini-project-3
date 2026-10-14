<?php
// helpers/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /inventory-app/public/login.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        die("<h3>Akses Ditolak!</h3><p>Halaman ini khusus untuk Administrator.</p><a href='/inventory-app/public/dashboard.php'>Kembali ke Dashboard</a>");
    }
}