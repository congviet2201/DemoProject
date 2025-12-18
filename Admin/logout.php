<?php
require_once __DIR__ . '/../model/session.php';
unset($_SESSION['is_admin']);
header('Location: /Admin/login.php');
exit();
?>