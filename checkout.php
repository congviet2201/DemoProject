<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';

// Kiểm tra giỏ hàng không trống
if (empty($_SESSION['cart'])) {
    $_SESSION['flash_message'] = "Giỏ hàng trống. Vui lòng thêm sản phẩm trước.";
    header('Location: /cart.php');
    exit;
}

// Lấy thông tin người dùng nếu đã đăng nhập
$user_info = [
    'fullname' => '',
    'email' => '',
    'phone' => '',
    'address' => ''
];

if (isset($_SESSION['user'])) {
    $user_info = [
        'fullname' => $_SESSION['user']['fullname'] ?? '',
        'email' => $_SESSION['user']['email'] ?? '',
        'phone' => $_SESSION['user']['phone'] ?? '',
        'address' => $_SESSION['user']['address'] ?? ''
    ];
}

// Xử lý form checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'COD');

    // Validation
    if (empty($fullname) || empty($email) || empty($phone) || empty($address)) {
        $_SESSION['checkout_error'] = "Vui lòng điền đầy đủ thông tin.";
        header('Location: /checkout.php');
        exit;
    }

    // Tính tổng tiền
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Tạo đơn hàng
    $order_id = 'ORD-' . time();
    $new_order = [
        'order_id' => $order_id,
        'fullname' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'total_amount' => $total_amount,
        'cart_items' => $_SESSION['cart'],
        'payment_method' => $payment_method,
        'order_date' => date('Y-m-d H:i:s'),
        'status' => 'pending'
    ];

    // Lưu vào session
    $_SESSION['order_info'] = $new_order;
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }
    $_SESSION['orders'][$order_id] = $new_order;
    // Xóa các sản phẩm đã mua khỏi giỏ hàng
    foreach ($new_order['cart_items'] as $pid => $p) {
        if (isset($_SESSION['cart'][$pid])) {
            unset($_SESSION['cart'][$pid]);
        }
    }
    // Nếu giỏ hàng trống, xoá luôn key
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }

    $_SESSION['flash_message'] = "Đặt hàng thành công! Mã đơn hàng: " . $order_id;
    // Notify admin by email about new order
    require_once __DIR__ . '/model/config.php';
    require_once __DIR__ . '/model/mail.php';

    $adminSubject = SITE_NAME . " - Đơn hàng mới " . $order_id;
    $adminMessage = "<h3>Đơn hàng mới: " . $order_id . "</h3>";
    $adminMessage .= "<p>Khách hàng: " . htmlspecialchars($fullname) . " (" . htmlspecialchars($email) . ")</p>";
    $adminMessage .= "<p>Tổng: " . number_format($total_amount) . " đ</p>";
    $adminMessage .= "<h4>Sản phẩm</h4><ul>";
    foreach ($new_order['cart_items'] as $it) {
        $adminMessage .= "<li>" . htmlspecialchars($it['name']) . " x" . intval($it['quantity']) . " - " . number_format($it['price']) . " đ</li>";
    }
    $adminMessage .= "</ul>";
    send_mail(ADMIN_EMAIL, $adminSubject, $adminMessage);

    header('Location: /order_success.php');
    exit;
}

$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thanh Toán | VIE Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/vie_logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .checkout-container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .checkout-box {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .checkout-title {
            color: #ff0066;
            border-bottom: 2px solid #ff0066;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .order-summary th {
            color: #ff0066;
        }

        .total-final {
            font-size: 24px;
            font-weight: bold;
            color: #d9534f;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/model/header.php'; ?>
    <div class="checkout-container container">
        <h2 class="text-center" style="color: #ff0066; margin-bottom: 30px;"><i class="fa fa-credit-card"></i> THANH TOÁN</h2>

        <div class="row">
            <!-- Form nhập thông tin -->
            <div class="col-md-6">
                <div class="checkout-box">
                    <h3 class="checkout-title">📋 Thông tin giao hàng</h3>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="fullname" class="form-control" required value="<?php echo htmlspecialchars($user_info['fullname']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user_info['email']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input type="tel" name="phone" class="form-control" required value="<?php echo htmlspecialchars($user_info['phone']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Địa chỉ giao hàng *</label>
                            <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($user_info['address']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Phương thức thanh toán *</label>
                            <select name="payment_method" class="form-control">
                                <option value="COD">Thanh toán khi nhận (COD)</option>
                                <option value="BANK">Chuyển khoản ngân hàng</option>
                                <option value="CARD">Thẻ tín dụng</option>
                            </select>
                        </div>

                        <div style="margin-top: 20px;">
                            <a href="/cart.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Quay về giỏ hàng</a>
                            <button type="submit" class="btn btn-success pull-right" style="border-radius: 5px; padding: 10px 30px;">
                                <i class="fa fa-check-circle"></i> ĐẶT HÀNG
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-md-6">
                <div class="checkout-box">
                    <h3 class="checkout-title">🛒 Tóm tắt đơn hàng</h3>
                    <table class="table order-summary">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($item['price']); ?> đ</td>
                                    <td><?php echo number_format($item['price'] * $item['quantity']); ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <hr>

                    <div style="font-size: 18px; margin-bottom: 10px;">
                        <strong>Tổng cộng:</strong> <span class="total-final"><?php echo number_format($total_price); ?> đ</span>
                    </div>

                    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 20px;">
                        <p><i class="fa fa-truck"></i> <strong>Phí vận chuyển:</strong> Miễn phí</p>
                        <p><i class="fa fa-money"></i> <strong>Tổng thanh toán:</strong> <span class="total-final"><?php echo number_format($total_price); ?> đ</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("model/footer.php"); ?>
</body>

</html>