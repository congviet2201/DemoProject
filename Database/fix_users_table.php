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

    // Xóa bảng cũ nếu tồn tại
    $conn->exec("DROP TABLE IF EXISTS users");
    echo "<h3 style='color: orange;'>⚠️ Đã xóa bảng users cũ!</h3>";

    // Tạo bảng users mới với schema đúng
    $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL COMMENT 'Họ tên',
        username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên đăng nhập',
        email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email',
        phone VARCHAR(20) NOT NULL COMMENT 'Số điện thoại',
        address VARCHAR(255) NOT NULL COMMENT 'Địa chỉ',
        password VARCHAR(255) NOT NULL COMMENT 'Mật khẩu (bcrypt hash - 60 ký tự)',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_username (username),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->exec($sql);
    echo "<h3 style='color: green;'>✅ Đã tạo bảng 'users' mới thành công!</h3>";
    echo "<p>Cột password: VARCHAR(255) - Đủ chứa bcrypt hash (60 ký tự)</p>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</h3>";
}

// Đóng kết nối
$conn = null;
?>
