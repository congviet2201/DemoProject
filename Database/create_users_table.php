<?php
// Cấu hình Database
$servername = "localhost";
$username = "root";
$password = "77171771";
$dbname = "fashion_mylishop";

try {
    // Tạo kết nối
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tạo bảng users nếu chưa tồn tại
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL COMMENT 'Họ tên',
        username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên đăng nhập',
        email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email',
        phone VARCHAR(20) NOT NULL COMMENT 'Số điện thoại',
        address VARCHAR(255) NOT NULL COMMENT 'Địa chỉ',
        password VARCHAR(255) NOT NULL COMMENT 'Mật khẩu (bcrypt hash)',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
        INDEX idx_username (username),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->exec($sql);
    echo "<h3 style='color: green;'>✅ Đã tạo bảng 'users' thành công!</h3>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</h3>";
}

// Đóng kết nối
$conn = null;
?>
