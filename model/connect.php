<?php
$host = "localhost";
$user = "root";
$password = "77171771";
$database = "fashion_mylishop";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
