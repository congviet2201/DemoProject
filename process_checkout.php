<?php
// File: process_checkout.php (CHẾ ĐỘ KHÔNG CẦN DB)

require_once __DIR__ . '/model/session.php';

// BỎ QUA KẾT NỐI CSDL
// require_once('model/connect.php'); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /checkout.php');
    exit();
}

// 1. Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    $_SESSION['checkout_error'] = "Giỏ hàng trống, không thể thanh toán.";
    // Redirect to absolute cart path to avoid relative paths from subfolders
    header('Location: /cart.php');
    exit();
}

// 2. Lấy dữ liệu từ form (chỉ để kiểm tra xem form đã được điền chưa)
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$total_amount = floatval($_POST['total_amount'] ?? 0);
// $user_id = $_SESSION['user']['id'] ?? NULL; // Không dùng

// 3. Kiểm tra dữ liệu bắt buộc (Nếu thiếu, vẫn báo lỗi)
if (empty($fullname) || empty($email) || empty($phone) || empty($address) || $total_amount <= 0) {
    $_SESSION['checkout_error'] = "Vui lòng điền đầy đủ thông tin và đảm bảo tổng tiền hợp lệ.";
    header('Location: /checkout.php');
    exit();
}


// Gán một ID đơn hàng giả lập 
$order_id = rand(100000, 999999); // Mã đơn hàng giả lập

// Tạo mảng thông tin đơn hàng
$new_order = [
    'order_id' => $order_id,
    'fullname' => $fullname,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'total_amount' => $total_amount,
    'cart_items' => $_SESSION['cart'],
    'payment_method' => $_POST['payment_method'] ?? 'COD',
    'order_date' => date('Y-m-d H:i:s'),
    'status' => 'pending' // pending, confirmed, shipping, delivered
];

// Lưu vào session - đơn hàng hiện tại
$_SESSION['order_info'] = $new_order;

// Khởi tạo mảng orders nếu chưa có
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Thêm đơn hàng vào danh sách đơn hàng
$_SESSION['orders'][$order_id] = $new_order;

// Lưu thông tin thành công vào flash message.
// Lưu ý: không xóa giỏ hàng để giữ lại sản phẩm sau khi mua (theo yêu cầu)
$_SESSION['flash_message'] = "Đặt hàng thành công! Mã đơn hàng của bạn là {$order_id}.";

header('Location: /order_success.php');
exit();
