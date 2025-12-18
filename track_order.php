<?php
if (session_status() == PHP_SESSION_NONE) {
    require_once __DIR__ . '/model/session.php';
}
require_once __DIR__ . '/model/header.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;

// Kiểm tra nếu có order_id, lấy từ session
if ($order_id && isset($_SESSION['orders'][$order_id])) {
    $order = $_SESSION['orders'][$order_id];
} elseif (!$order_id && isset($_SESSION['order_info'])) {
    // Nếu không có order_id trong URL, sử dụng đơn hàng gần đây nhất
    $order = $_SESSION['order_info'];
    $order_id = $order['order_id'];
}

// Hàm lấy nhãn trạng thái
function getStatusLabel($status)
{
    $labels = [
        'pending' => ['label' => 'Chờ xác nhận', 'color' => 'warning', 'icon' => 'clock-o'],
        'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'info', 'icon' => 'check'],
        'shipping' => ['label' => 'Đang giao hàng', 'color' => 'primary', 'icon' => 'truck'],
        'delivered' => ['label' => 'Đã giao', 'color' => 'success', 'icon' => 'check-circle']
    ];
    return $labels[$status] ?? ['label' => 'Không xác định', 'color' => 'secondary', 'icon' => 'question'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        .track-container {
            margin: 40px 0;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .order-id {
            font-size: 2em;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            margin-bottom: 30px;
            position: relative;
            padding-left: 50px;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .timeline-item.active .timeline-marker {
            background: #ff0066;
        }

        .timeline-item.active .timeline-title {
            font-weight: bold;
            color: #ff0066;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            width: 2px;
            height: 30px;
            background: #ddd;
        }

        .timeline-item.active::before {
            background: #ff0066;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-title {
            font-size: 1.1em;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .timeline-desc {
            color: #666;
            font-size: 0.9em;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ff0066;
            margin-bottom: 20px;
        }

        .info-box h4 {
            color: #ff0066;
            margin-top: 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .product-table th {
            background: #ff0066;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .product-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>

    <div class="main container track-container">
        <?php if ($order): ?>
            <div class="order-header">
                <div class="order-id">
                    <i class="fa fa-shopping-bag"></i> Đơn hàng #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?>
                </div>
                <?php
                $status_info = getStatusLabel($order['status']);
                echo '<div class="status-badge" style="background-color: var(--bs-' . $status_info['color'] . ', #' . ($status_info['color'] == 'warning' ? 'ffc107' : ($status_info['color'] == 'info' ? '17a2b8' : ($status_info['color'] == 'primary' ? '007bff' : '28a745'))) . ');">';
                echo '<i class="fa fa-' . $status_info['icon'] . '"></i> ' . $status_info['label'];
                echo '</div>';
                ?>
            </div>

            <!-- Timeline Trạng thái -->
            <div style="background: white; padding: 30px; border-radius: 8px; margin-bottom: 30px;">
                <h3 style="color: #ff0066; border-bottom: 2px solid #ff0066; padding-bottom: 10px;">Quá trình xử lý đơn hàng</h3>
                <div class="timeline">
                    <?php
                    $statuses = ['pending', 'confirmed', 'shipping', 'delivered'];
                    $status_names = ['Chờ xác nhận', 'Đã xác nhận', 'Đang giao hàng', 'Đã giao'];
                    $status_icons = ['clock-o', 'check', 'truck', 'check-circle'];

                    $current_index = array_search($order['status'], $statuses);

                    foreach ($statuses as $index => $status):
                        $is_active = $index <= $current_index;
                    ?>
                        <div class="timeline-item <?php echo $is_active ? 'active' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="fa fa-<?php echo $status_icons[$index]; ?>"></i>
                            </div>
                            <div class="timeline-title"><?php echo $status_names[$index]; ?></div>
                            <div class="timeline-desc">
                                <?php
                                if ($status === 'pending') echo 'Đang chờ xác nhận từ cửa hàng';
                                elseif ($status === 'confirmed') echo 'Đơn hàng đã được xác nhận';
                                elseif ($status === 'shipping') echo 'Đơn hàng đang trong quá trình giao';
                                elseif ($status === 'delivered') echo 'Đơn hàng đã được giao thành công';
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box">
                        <h4><i class="fa fa-map-marker"></i> Thông Tin Giao Hàng</h4>
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-weight: bold; width: 40%; padding: 8px 0;">Họ tên:</td>
                                <td style="padding: 8px 0;"><?php echo htmlspecialchars($order['fullname']); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; padding: 8px 0;">Email:</td>
                                <td style="padding: 8px 0;"><?php echo htmlspecialchars($order['email']); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; padding: 8px 0;">Số điện thoại:</td>
                                <td style="padding: 8px 0;"><?php echo htmlspecialchars($order['phone']); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; padding: 8px 0;">Địa chỉ:</td>
                                <td style="padding: 8px 0;"><?php echo htmlspecialchars($order['address']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <h4><i class="fa fa-info-circle"></i> Thông Tin Đơn Hàng</h4>
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-weight: bold; width: 40%; padding: 8px 0;">Ngày đặt:</td>
                                <td style="padding: 8px 0;"><?php echo $order['order_date']; ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; padding: 8px 0;">Phương thức:</td>
                                <td style="padding: 8px 0;">
                                    <?php echo $order['payment_method'] === 'COD' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; padding: 8px 0;">Tổng tiền:</td>
                                <td style="padding: 8px 0; color: #d9534f; font-weight: bold; font-size: 1.1em;">
                                    <?php echo number_format($order['total_amount']); ?>đ
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div style="background: white; padding: 30px; border-radius: 8px; margin-top: 30px;">
                <h3 style="color: #ff0066; border-bottom: 2px solid #ff0066; padding-bottom: 10px;">Chi tiết sản phẩm</h3>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
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
                                <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($item['price']); ?>đ</td>
                                <td style="text-align: right; font-weight: bold;"><?php echo number_format($sub_total); ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Nút hành động -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="my_orders.php" class="btn btn-info" style="margin-right: 10px;">
                    <i class="fa fa-list"></i> Xem tất cả đơn hàng
                </a>
                <a href="index.php" class="btn btn-primary">
                    <i class="fa fa-home"></i> Tiếp tục mua sắm
                </a>
            </div>

        <?php else: ?>
            <div class="error-box">
                <h4><i class="fa fa-exclamation-circle"></i> Không tìm thấy đơn hàng</h4>
                <p>Mã đơn hàng không tồn tại hoặc session đã hết hạn. Vui lòng kiểm tra lại.</p>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="my_orders.php" class="btn btn-primary">Xem danh sách đơn hàng</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    require_once('model/footer.php');
    ?>
</body>

</html>