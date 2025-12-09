<?php
// File: process_checkout.php (CHẾ ĐỘ KHÔNG CẦN DB)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// BỎ QUA KẾT NỐI CSDL
// require_once('model/connect.php'); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

// 1. Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    $_SESSION['checkout_error'] = "Giỏ hàng trống, không thể thanh toán.";
    header('Location: cart.php');
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
    header('Location: checkout.php');
    exit();
}


// Gán một ID đơn hàng giả lập 
$order_id = rand(1, 999999); // Mã đơn hàng giả lập

// 5. Dọn dẹp session giỏ hàng và chuyển hướng
unset($_SESSION['cart']);
$_SESSION['flash_message'] = "Đặt hàng thành công! Mã đơn hàng của bạn là {$order_id}.";

header('Location: order_success.php');
exit();
