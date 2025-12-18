<?php
require_once __DIR__ . '/../model/session.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /Admin/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body style="padding:20px;">
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, admin. Use the links below to manage the store.</p>
    <ul>
        <li><a href="product-list.php">Quản lý sản phẩm</a> (sử dụng các file trong thư mục Admin)</li>
        <li><a href="orders.php">Quản lý đơn hàng</a></li>
        <li><a href="logout.php" style="color:red;">Đăng xuất</a></li>
    </ul>
</div>
</body>
</html>