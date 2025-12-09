<?php
// File: user/profile.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    header('Location: login.php');
    exit();
}

// Lấy thông tin người dùng từ Session
$user = $_SESSION['user'];

// 2. Include Header (Giả định nằm ở thư mục gốc)
// Thay đổi đường dẫn nếu cấu trúc file của bạn khác
// require_once('../model/connect.php'); // Dòng này thường dùng khi có Database
require_once('../model/header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - MyLiShop</title>
    <style>
        .profile-container {
            margin-top: 30px;
            margin-bottom: 50px;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .profile-header {
            color: #ff0066;
            margin-bottom: 25px;
            border-bottom: 2px solid #ff0066;
            padding-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            padding-right: 15px;
        }

        .info-group {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px dotted #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="profile-container">
                <h2 class="profile-header"><i class="fa fa-user-circle-o"></i> Thông tin cá nhân</h2>

                <div class="info-group">
                    <div class="row">
                        <div class="col-xs-4 info-label">Mã ID:</div>
                        <div class="col-xs-8"><?= $user['id'] ?? 'N/A' ?></div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="row">
                        <div class="col-xs-4 info-label">Họ và tên:</div>
                        <div class="col-xs-8"><?= $user['fullname'] ?? 'N/A' ?></div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="row">
                        <div class="col-xs-4 info-label">Email:</div>
                        <div class="col-xs-8"><?= $user['email'] ?? 'N/A' ?></div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="row">
                        <div class="col-xs-4 info-label">Điện thoại:</div>
                        <div class="col-xs-8"><?= $user['phone'] ?? 'N/A' ?></div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="row">
                        <div class="col-xs-4 info-label">Địa chỉ:</div>
                        <div class="col-xs-8"><?= $user['address'] ?? 'N/A' ?></div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 30px;">
                    <a href="edit_profile.php" class="btn btn-warning"><i class="fa fa-pencil"></i> Chỉnh sửa thông tin</a>
                    <a href="../index.php" class="btn btn-info"><i class="fa fa-home"></i> Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>

    <?php include('../model/footer.php'); ?>
</body>

</html>