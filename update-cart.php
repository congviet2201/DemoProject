// File: update-cart.php (Chức năng: Cập nhật và Xóa giỏ hàng)
<?php
session_start();

// Kiểm tra giỏ hàng có tồn tại không
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// SỬA: Chuyển hướng đến cart.php (để đồng bộ với header.php)
$redirect_url = 'cart.php';

// --- 1. Xử lý xóa sản phẩm (DELETE) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $product_name = $_SESSION['cart'][$product_id]['name'];
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['flash_message'] = "Đã xóa **" . $product_name . "** khỏi giỏ hàng!";
    }
    header('Location: ' . $redirect_url);
    exit();
}

// --- 2. Xử lý cập nhật số lượng (UPDATE) ---
if (isset($_POST['update_cart']) && isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    $updated_count = 0;

    foreach ($_POST['quantity'] as $product_id => $new_quantity) {
        $product_id = intval($product_id);
        $new_quantity = intval($new_quantity);

        if (array_key_exists($product_id, $_SESSION['cart'])) {
            // Kiểm tra số lượng hợp lệ
            if ($new_quantity > 0) {
                // Cập nhật số lượng
                $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
                $updated_count++;
            } else {
                // Nếu số lượng <= 0, coi như xóa sản phẩm đó
                unset($_SESSION['cart'][$product_id]);
                $updated_count++;
            }
        }
    }

    if ($updated_count > 0) {
        $_SESSION['flash_message'] = "Đã cập nhật giỏ hàng thành công!";
    } else {
        $_SESSION['flash_message'] = "Giỏ hàng không có thay đổi nào.";
    }

    header('Location: ' . $redirect_url);
    exit();
}

// Nếu không có hành động nào được thực hiện, chuyển hướng về giỏ hàng
header('Location: ' . $redirect_url);
exit();
?>