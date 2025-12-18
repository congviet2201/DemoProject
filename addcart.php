<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';

// Kiểm tra ID sản phẩm
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /index.php');
    exit;
}

$product_id = intval($_GET['id']);

// Truy vấn sản phẩm từ DB
try {
    $sql = "SELECT id, name, price, image FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: /index.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
    exit;
}

// Xử lý giỏ hàng (session-based)
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (array_key_exists($product_id, $_SESSION['cart'])) {
    // Nếu sản phẩm đã tồn tại, tăng số lượng
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Nếu sản phẩm chưa tồn tại, thêm mới
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1
    ];
}

// Flash message
$_SESSION['flash_message'] = "Đã thêm <strong>" . htmlspecialchars($product['name']) . "</strong> vào giỏ hàng!";

// Redirect to cart
header('Location: /cart.php');
exit;
