<?php
// Reset tất cả session
session_start();

// Xóa toàn bộ session variables
session_unset();

// Xóa session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hủy session
session_destroy();

// Xóa session từ storage
echo "<h3 style='color: green;'>✅ Đã xóa toàn bộ thông tin session!</h3>";
echo "<p>Chuyển hướng trong 2 giây...</p>";
echo "<script>setTimeout(function() { window.location.href = '../index.php'; }, 2000);</script>";
