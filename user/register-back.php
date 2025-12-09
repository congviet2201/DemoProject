<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once '../model/connect.php';

if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email    = $_POST['email'];
    $address  = $_POST['address'];
    $phone    = $_POST['phone'];

    $sql = "INSERT INTO users (fullname, username, password, email, phone, address, role)
            VALUES (:fullname, :username, md5(:password), :email, :phone, :address, 1)";
    $stmt = $conn->prepare($sql);

    $res = $stmt->execute([
        ':fullname' => $fullname,
        ':username' => $username,
        ':password' => $password,
        ':email'    => $email,
        ':phone'    => $phone,
        ':address'  => $address
    ]);

    if ($res) {
        header("location:login.php?rs=success");
        exit();
    } else {
        header("location:login.php?rf=fail");
        exit();
    }
}
