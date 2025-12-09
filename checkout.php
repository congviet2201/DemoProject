<?php
// Sử dụng session_start() nếu chưa được gọi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Giả định các file này nằm ở model/
require_once('model/connect.php');
require_once('model/header.php');

// 1. Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    // Nếu giỏ hàng trống, chuyển hướng về trang giỏ hàng
    $_SESSION['flash_message'] = "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm để thanh toán.";
    header('Location: cart.php');
    exit();
}

$total_cart_price = 0;
// Lấy thông tin người dùng nếu đã đăng nhập để điền sẵn vào form
$user_info = [
    'fullname' => '',
    'email' => '',
    'phone' => '',
    'address' => ''
];

if (isset($_SESSION['user'])) {
    // Lấy thông tin từ session user (đã kiểm tra trong file login.php trước đó)
    $user_info['fullname'] = $_SESSION['user']['fullname'] ?? '';
    $user_info['email'] = $_SESSION['user']['email'] ?? '';
    $user_info['phone'] = $_SESSION['user']['phone'] ?? '';
    $user_info['address'] = $_SESSION['user']['address'] ?? '';
}

// Lấy thông báo lỗi từ process_checkout.php nếu có
$error_message = $_SESSION['checkout_error'] ?? null;
unset($_SESSION['checkout_error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thanh Toán | Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logohong.png">
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
    <div class="main container checkout-container">
        <h2 class="text-center checkout-title">Xác Nhận Đơn Hàng & Thanh Toán</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i> Lỗi: <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="process_checkout.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="checkout-box">
                        <h4 class="checkout-title">Thông Tin Giao Hàng</h4>
                        <?php if (!isset($_SESSION['user'])): ?>
                            <div class="alert alert-warning">
                                Bạn đang thanh toán với tư cách Khách. Vui lòng điền thông tin chi tiết.
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="fullname">Họ và Tên (*)</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user_info['fullname']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email (*)</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số Điện Thoại (*)</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user_info['phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa Chỉ Giao Hàng (*)</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user_info['address']); ?></textarea>
                        </div>

                        <h4 class="checkout-title" style="margin-top: 30px;">Phương Thức Thanh Toán</h4>
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="payment_method" value="COD" checked>
                                    **Thanh toán khi nhận hàng (COD)**
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="payment_method" value="BANK_TRANSFER">
                                    Chuyển khoản ngân hàng (Sẽ liên hệ sau)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="checkout-box">
                        <h4 class="checkout-title">Tóm Tắt Đơn Hàng</h4>
                        <table class="table order-summary">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-right">SL</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $item) :
                                    $sub_total = $item['price'] * $item['quantity'];
                                    $total_cart_price += $sub_total;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="text-right"><?php echo $item['quantity']; ?></td>
                                        <td class="text-right"><?php echo number_format($sub_total); ?> <sup>đ</sup></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">**Phí vận chuyển**</td>
                                    <td class="text-right">**Miễn phí**</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="total-final">**Tổng Cộng**</td>
                                    <td class="text-right total-final"><?php echo number_format($total_cart_price); ?> <sup>đ</sup></td>
                                </tr>
                            </tfoot>
                        </table>

                        <input type="hidden" name="total_amount" value="<?php echo $total_cart_price; ?>">

                        <div class="text-center" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-success btn-lg" style="width: 100%; border-radius: 25px; font-weight: bold;">
                                <i class="fa fa-shopping-bag"></i> Đặt Hàng Ngay
                            </button>
                            <p style="margin-top: 15px;">
                                <small>Bằng việc đặt hàng, bạn đồng ý với Điều khoản và Điều kiện của chúng tôi.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include("model/footer.php"); ?>

</body>

</html>