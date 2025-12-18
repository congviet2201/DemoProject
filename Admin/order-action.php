<?php
require_once __DIR__ . '/../model/session.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Admin/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Admin/orders.php');
    exit();
}

$order_id = $_POST['order_id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$order_id || !$status) {
    header('Location: /Admin/orders.php');
    exit();
}

// Find order in session orders
if (isset($_SESSION['orders'][$order_id])) {
    $_SESSION['orders'][$order_id]['status'] = $status;
    // also update order_info if matches
    if (isset($_SESSION['order_info']) && $_SESSION['order_info']['order_id'] == $order_id) {
        $_SESSION['order_info']['status'] = $status;
    }
}

header('Location: /Admin/orders.php');
exit();
?>