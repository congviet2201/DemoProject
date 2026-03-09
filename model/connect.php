<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "Office";

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
