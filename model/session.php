<?php
$lifetime = 60 * 60 * 24 * 30; // 30 ngày

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'domain' => '', // localhost FIX
    'secure' => false, // localhost
    'httponly' => true,
    'samesite' => 'Lax'
]);

ini_set('session.gc_maxlifetime', (string)$lifetime);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo cart
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
