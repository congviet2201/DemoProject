<?php
// Sử dụng session_start() nếu chưa được gọi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Giả định connect.php kết nối CSDL và tạo ra biến $conn (PDO)
require_once('model/connect.php');

// 1. Kiểm tra ID sản phẩm (Được truyền từ index.php)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php'); // Quay về trang chủ nếu thiếu ID
    exit();
}

$product_id = intval($_GET['id']);

// 2. Truy vấn Database để lấy thông tin sản phẩm
try {
    $sql = "SELECT id, name, price, image FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: index.php'); // Quay về trang chủ nếu không tìm thấy
        exit();
    }
} catch (PDOException $e) {
    // Xử lý lỗi CSDL 
    echo "Lỗi truy vấn CSDL: " . $e->getMessage();
    exit();
}

// 3. Xử lý logic giỏ hàng 
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (array_key_exists($product_id, $_SESSION['cart'])) {
    // Nếu đã có, tăng số lượng lên 1 ( dùng session lưu sản phẩm không mất)
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    // Nếu chưa có, thêm sản phẩm mới vào giỏ
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1
    ];
}

// 4. Chuyển hướng về trang giỏ hàng (cart.php)
$_SESSION['flash_message'] = "Đã thêm **" . $product['name'] . "** vào giỏ hàng!";

header('Location: cart.php'); // SỬA: Chuyển hướng đến cart.php
exit();
