<?php
require_once __DIR__ . '/model/session.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$redirect_url = '/cart.php';

/* ===== XÓA SẢN PHẨM ===== */
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && isset($_GET['action'], $_GET['id'])
    && $_GET['action'] === 'delete'
) {
    $product_id = (int) $_GET['id'];

    if (isset($_SESSION['cart'][$product_id])) {
        $name = $_SESSION['cart'][$product_id]['name'];
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['flash_message'] = "Đã xóa <strong>$name</strong> khỏi giỏ hàng.";
    }

    header("Location: $redirect_url");
    exit;
}

/* ===== CẬP NHẬT GIỎ HÀNG ===== */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['update_cart'], $_POST['quantity'])
) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $id  = (int) $id;
        $qty = (int) $qty;

        if (!isset($_SESSION['cart'][$id])) continue;

        if ($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }

    $_SESSION['flash_message'] = "Giỏ hàng đã được cập nhật.";
    header("Location: $redirect_url");
    exit;
}

header("Location: $redirect_url");
exit;
