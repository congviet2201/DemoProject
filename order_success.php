<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/header.php';

$order = null;
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id > 0 && isset($_SESSION['orders'][$order_id])) {
    $order = $_SESSION['orders'][$order_id];
} elseif (isset($_SESSION['order_info'])) {
    $order = $_SESSION['order_info'];
    $order_id = $order['order_id'] ?? 0;
}

/* Validate dữ liệu bắt buộc */
if (!$order || !isset($order['cart_items'], $order['total_amount'])) {
    $order = null;
}
?>
<?php if ($order): ?>
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="color: #ff0066; border-bottom: 2px solid #ff0066; padding-bottom: 10px;">Chi Tiết Đơn Hàng</h3>

        <div class="row">
            <div class="col-md-6">
                <h4>Thông Tin Giao Hàng</h4>
                <table class="table">
                    <tr>
                        <td><strong>Mã đơn hàng:</strong></td>
                        <td><?php echo $order['order_id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Họ tên:</strong></td>
                        <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Số điện thoại:</strong></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Địa chỉ:</strong></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phương thức thanh toán:</strong></td>
                        <td><?php echo $order['payment_method'] === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Ngày đặt:</strong></td>
                        <td><?php echo $order['order_date']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h4>Thông Tin Sản Phẩm</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>SL</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['cart_items'] as $item):
                            $sub_total = $item['price'] * $item['quantity'];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price']); ?>đ</td>
                                <td><?php echo number_format($sub_total); ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid #ff0066;">
                            <td colspan="3" style="text-align: right; font-weight: bold;">TỔNG CỘNG:</td>
                            <td style="font-weight: bold; color: #d9534f; font-size: 1.1em;">
                                <?php echo number_format($order['total_amount']); ?>đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<p style="text-align: center; margin-top: 20px;">Cảm ơn bạn đã tin tưởng và mua hàng tại VIE Shop. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất!</p>

<div style="text-align: center; margin-top: 30px;">
    <a href="index.php" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> Tiếp tục mua sắm</a>
</div>
</div>
<?php require_once('model/footer.php'); ?>