<?php
session_start();
require_once('../model/connect.php');

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Tìm user theo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            // Lưu session
            $_SESSION['user'] = [
                "id"       => $user['id'],
                "fullname" => $user['fullname'],
                "email"    => $user['email']
            ];

            header("Location: ../index.php");
            exit();
        }
    }

    header("Location: login.php?error=1");
    exit();
}
