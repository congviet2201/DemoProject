<?php
// File: user/edit_profile.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Lấy thông tin người dùng hiện tại và thông báo lỗi (nếu có)
$user = $_SESSION['user'];
$error = $_SESSION['edit_error'] ?? null;
unset($_SESSION['edit_error']); // Xóa lỗi sau khi hiển thị

// Xử lý POST request khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lấy dữ liệu từ form
    $new_fullname = trim($_POST['fullname'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_phone = trim($_POST['phone'] ?? '');
    $new_address = trim($_POST['address'] ?? '');

    // 2. Validation (Kiểm tra cơ bản)
    if (empty($new_fullname) || empty($new_email) || empty($new_phone) || empty($new_address)) {
        $_SESSION['edit_error'] = "Vui lòng điền đầy đủ tất cả các trường thông tin.";
        header('Location: edit_profile.php');
        exit();
    }

    // Validation email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['edit_error'] = "Địa chỉ email không hợp lệ.";
        header('Location: edit_profile.php');
        exit();
    }


    // --- 3. MÔ PHỎNG CẬP NHẬT (Chỉ cập nhật Session) ---

    // Cập nhật dữ liệu mới vào Session
    $_SESSION['user']['fullname'] = $new_fullname;
    $_SESSION['user']['email'] = $new_email;
    $_SESSION['user']['phone'] = $new_phone;
    $_SESSION['user']['address'] = $new_address;

    // Thiết lập thông báo thành công
    $_SESSION['flash_message'] = "✅ Cập nhật thông tin thành công! (Dữ liệu chỉ được lưu vào Session).";

    // Chuyển hướng về trang profile để hiển thị kết quả
    header('Location: profile.php');
    exit();
}

// 4. Hiển thị form (GET Request)
require_once('../model/header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin - MyLiShop</title>
    <style>
        .edit-container {
            margin-top: 30px;
            margin-bottom: 50px;
            padding: 30px;
            border: 1px solid #ff0066;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(255, 0, 102, 0.1);
            background-color: #fff;
        }

        .edit-header {
            color: #ff0066;
            margin-bottom: 25px;
            border-bottom: 2px solid #ff0066;
            padding-bottom: 10px;
        }

        .form-group label {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="edit-container">
                <h2 class="edit-header"><i class="fa fa-pencil-square-o"></i> Chỉnh sửa thông tin cá nhân</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="edit_profile.php" method="POST">

                    <div class="form-group">
                        <label>Mã ID:</label>
                        <p class="form-control-static"><?= htmlspecialchars($user['id'] ?? 'N/A') ?></p>
                    </div>

                    <div class="form-group">
                        <label for="fullname">Họ và tên:</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Điện thoại:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ:</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary" style="background-color: #ff0066; border-color: #ff0066;">
                        <i class="fa fa-save"></i> Cập nhật thông tin
                    </button>
                    <a href="profile.php" class="btn btn-default">
                        <i class="fa fa-times"></i> Hủy
                    </a>
                </form>
            </div>
        </div>
    </div>

    <?php include('../model/footer.php'); ?>
</body>

</html>