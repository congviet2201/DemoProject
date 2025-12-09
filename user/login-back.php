<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../model/connect.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = :username AND password = md5(:password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        // LẤY THÔNG TIN USER
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // TẠO SESSION ĐÚNG ĐỊNH DẠNG header.php CẦN
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'fullname' => $user['fullname'],
            'username' => $user['username'],
            'email'    => $user['email']
        ];

        header("location:../index.php?login=success");
        exit();
    } else {
        $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không hợp lệ!';
        header("location:../user/login.php?error=wrong");
        exit();
    }
}
