<?php
require_once __DIR__ . '/../model/session.php';
require_once __DIR__ . '/../model/connect.php';

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header("Location: login.php?error=1");
    exit;
}

// LOGIN THÀNH CÔNG
$_SESSION['user'] = [
    'id' => $user['id'],
    'fullname' => $user['fullname'],
    'username' => $user['username'],
    'email' => $user['email']
];
$_SESSION['username'] = $user['username'];
$_SESSION['user_id']  = $user['id'];

header("Location: /index.php");
exit;
