<?php
require_once __DIR__ . '/../model/session.php';
require_once __DIR__ . '/../model/connect.php';
require_once __DIR__ . '/../model/config.php';
require_once __DIR__ . '/../model/mail.php';

if (isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirmPassword'] ?? '';

// Check mật khẩu
if ($password !== $confirm) {
    header("Location: register.php?error=password");
    exit;
}

// Check trùng username/email
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);
if ($stmt->rowCount()) {
    header("Location: register.php?error=exists");
    exit;
}

// Hash mật khẩu
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user with all fields
$stmt = $conn->prepare("
    INSERT INTO users (fullname, username, email, phone, address, password)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([$fullname, $username, $email, $phone, $address, $hash]);
$user_id = $conn->lastInsertId();

// AUTO LOGIN (QUAN TRỌNG)
$_SESSION['user'] = [
    'id' => $user_id,
    'fullname' => $fullname,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
    'address' => $address
];
$_SESSION['username'] = $username;
$_SESSION['user_id']  = $user_id;

header("Location: /index.php");
    exit;
}
?>

// Send welcome email to user and notify admin
$subject = SITE_NAME . " - Chào mừng " . htmlspecialchars($fullname);
$message = "<p>Xin chào " . htmlspecialchars($fullname) . ",</p>";
$message .= "<p>Cảm ơn bạn đã đăng ký tại " . SITE_NAME . ".</p>";
$message .= "<p>Username của bạn: " . htmlspecialchars($username) . "</p>";

// Send to user
send_mail_simple($email, $subject, $message);

// Notify admin
$adminMsg = "<p>Người dùng mới đã đăng ký:</p>";
$adminMsg .= "<p>Họ tên: " . htmlspecialchars($fullname) . "<br>Username: " . htmlspecialchars($username) . "<br>Email: " . htmlspecialchars($email) . "</p>";
send_mail_simple(ADMIN_EMAIL, SITE_NAME . " - New Registration", $adminMsg);

