<?php
require_once __DIR__ . '/../model/session.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Admin/login.php');
    exit();
}

$orders = $_SESSION['orders'] ?? [];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        .status-badge { padding:6px 10px; border-radius:4px; color:#fff; }
        .pending{ background:#ffc107; color:#000; }
        .confirmed{ background:#17a2b8; }
        .shipping{ background:#007bff; }
        .delivered{ background:#28a745; }
    </style>
</head>
<body style="padding:20px;">
<div class="container">
    <h2>Danh sách đơn hàng</h2>
    <p><a href="index.php">Quay về Dashboard</a> | <a href="logout.php">Đăng xuất</a></p>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Chưa có đơn hàng nào.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $id => $o): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($o['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($o['fullname']); ?><br><small><?php echo htmlspecialchars($o['email']); ?></small></td>
                        <td><?php echo count($o['cart_items']); ?></td>
                        <td><?php echo number_format($o['total_amount']); ?>đ</td>
                        <td>
                            <span class="status-badge <?php echo $o['status']; ?>"><?php echo ucfirst($o['status']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($o['order_date']); ?></td>
                        <td>
                            <form method="post" action="order-action.php" style="display:inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($o['order_id']); ?>">
                                <select name="status" class="form-control" style="width:140px; display:inline-block;">
                                    <option value="pending" <?php echo $o['status']=='pending'? 'selected':''; ?>>pending</option>
                                    <option value="confirmed" <?php echo $o['status']=='confirmed'? 'selected':''; ?>>confirmed</option>
                                    <option value="shipping" <?php echo $o['status']=='shipping'? 'selected':''; ?>>shipping</option>
                                    <option value="delivered" <?php echo $o['status']=='delivered'? 'selected':''; ?>>delivered</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                            <a href="../track_order.php?order_id=<?php echo urlencode($o['order_id']); ?>" class="btn btn-default">Xem</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>