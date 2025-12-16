<?php
require_once('../model/connect.php');

if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirmPassword'];

    // Kiểm tra mật khẩu trùng khớp
    if ($password !== $confirm) {
        header("Location: register.php?rf=1");
        exit();
    }

    // Kiểm tra email trùng
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        header("Location: register.php?rf=1");
        exit();
    }

    // Mã hóa mật khẩu
    $hashPass = password_hash($password, PASSWORD_DEFAULT);

    // Lưu DB
    $stmt = $conn->prepare("
        INSERT INTO users(fullname, username, email, phone, address, password)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if ($stmt->execute([$fullname, $username, $email, $phone, $address, $hashPass])) {
        header("Location: login.php?rs=1");
    } else {
        header("Location: register.php?rf=1");
    }
}
