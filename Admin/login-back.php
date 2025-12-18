<?php
require_once __DIR__ . '/../model/session.php';

// VERY SIMPLE admin auth — change this to a secure method in production
$ADMIN_PASSWORD = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST['password'] ?? '';
    if ($p === $ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
        // redirect to admin dashboard
        header('Location: /Admin/index.php');
        exit();
    } else {
        header('Location: /Admin/login.php?error=Invalid+password');
        exit();
    }
}

header('Location: /Admin/login.php');
exit();
?>