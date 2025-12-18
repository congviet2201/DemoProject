<?php
require_once __DIR__ . '/../model/session.php';

// Xóa session
session_unset();
session_destroy();

// Xóa cookie session
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

header("Location: ../index.php");
exit;
