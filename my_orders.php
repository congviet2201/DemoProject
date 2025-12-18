<?php
if (session_status() == PHP_SESSION_NONE) {
    require_once __DIR__ . '/model/session.php';
}
require_once('model/header.php');

// Hàm lấy nhãn trạng thái
function getStatusBadge($status)
{
    $badges = [
        'pending' => '<span style="background: #ffc107; color: #000; padding: 5px 10px; border-radius: 20px; font-size: 0.85em;"><i class="fa fa-clock-o"></i> Chờ xác nhận</span>',
        'confirmed' => '<span style="background: #17a2b8; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.85em;"><i class="fa fa-check"></i> Đã xác nhận</span>',
        'shipping' => '<span style="background: #007bff; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.85em;"><i class="fa fa-truck"></i> Đang giao</span>',
        'delivered' => '<span style="background: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.85em;"><i class="fa fa-check-circle"></i> Đã giao</span>'
    ];
    return $badges[$status] ?? '<span style="background: #6c757d; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.85em;">Không xác định</span>';
}

$orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];
$total_orders = count($orders);
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        .orders-container {
            margin: 40px 0;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #ff0066;
        }

        .stat-label {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .order-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ff0066;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .order-card:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .order-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-size: 1.2em;
            font-weight: bold;
            color: #ff0066;
        }

        .order-date {
            color: #666;
            font-size: 0.9em;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .info-item {
            font-size: 0.9em;
        }

        .info-label {
            color: #666;
            font-weight: bold;
        }

        .info-value {
            color: #333;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-total {
            font-size: 1.3em;
            font-weight: bold;
            color: #d9534f;
        }

        .btn-track {
            background: #ff0066;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
        }

        .btn-track:hover {
            background: #e60052;
            color: white;
            text-decoration: none;
        }

        .empty-box {
            background: white;
            padding: 50px;
            text-align: center;
            border-radius: 8px;
        }

        .empty-box i {
            font-size: 3em;
            color: #ddd;
            margin-bottom: 20px;
            display: block;
        }

        .empty-box h3 {
            color: #999;
            margin-bottom: 10px;
        }

        .empty-box p {
            color: #999;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="main container orders-container">
        <div class="page-header">
            <h1><i class="fa fa-shopping-bag"></i> Quản Lý Đơn Hàng</h1>
            <p>Theo dõi và quản lý tất cả các đơn hàng của bạn</p>
        </div>

        <!-- Thống kê -->
        <div class="orders-stats">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_orders; ?></div>
                <div class="stat-label">Tổng đơn hàng</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo count(array_filter($orders, function ($o) {
                                                return $o['status'] === 'pending';
                                            })); ?></div>
                <div class="stat-label">Chờ xác nhận</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo count(array_filter($orders, function ($o) {
                                                return $o['status'] === 'shipping';
                                            })); ?></div>
                <div class="stat-label">Đang giao</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo count(array_filter($orders, function ($o) {
                                                return $o['status'] === 'delivered';
                                            })); ?></div>
                <div class="stat-label">Đã giao</div>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <?php if ($total_orders > 0): ?>
            <h3 style="color: #ff0066; border-bottom: 2px solid #ff0066; padding-bottom: 10px; margin-top: 30px; margin-bottom: 20px;">
                <i class="fa fa-list"></i> Danh sách đơn hàng (<?php echo $total_orders; ?> đơn)
            </h3>

            <?php
            // Sắp xếp đơn hàng theo ngày mới nhất
            uasort($orders, function ($a, $b) {
                return strtotime($b['order_date']) - strtotime($a['order_date']);
            });

            foreach ($orders as $order):
            ?>
                <div class="order-card">
                    <div class="order-header-row">
                        <div>
                            <div class="order-id">#<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <div class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></div>
                        </div>
                        <div><?php echo getStatusBadge($order['status']); ?></div>
                    </div>

                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-label">Khách hàng</div>
                            <div class="info-value"><?php echo htmlspecialchars($order['fullname']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Số điện thoại</div>
                            <div class="info-value"><?php echo htmlspecialchars($order['phone']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo htmlspecialchars($order['email']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Thanh toán</div>
                            <div class="info-value">
                                <?php echo $order['payment_method'] === 'COD' ? 'COD (Khi nhận)' : 'Chuyển khoản'; ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Số sản phẩm</div>
                            <div class="info-value"><?php echo count($order['cart_items']); ?> sản phẩm</div>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div class="order-total">
                            Tổng: <?php echo number_format($order['total_amount']); ?>đ
                        </div>
                        <a href="track_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn-track">
                            <i class="fa fa-eye"></i> Chi tiết & Theo dõi
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-box">
                <i class="fa fa-shopping-cart"></i>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa tạo bất kỳ đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                <a href="index.php" class="btn btn-primary" style="display: inline-block;">
                    <i class="fa fa-shopping-bag"></i> Bắt đầu mua sắm
                </a>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="btn btn-default">
                <i class="fa fa-home"></i> Quay lại trang chủ
            </a>
        </div>
    </div>

    <?php require_once('model/footer.php'); ?>
</body>

</html>