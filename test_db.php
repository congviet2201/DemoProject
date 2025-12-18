<?php
require_once __DIR__ . '/model/connect.php';

echo "<h2>🔍 Database Test</h2>";

try {
    // Test 1: Connection
    echo "<p>✅ Database connection successful!</p>";
    
    // Test 2: Check users table
    $stmt = $conn->prepare("SHOW TABLES LIKE 'users'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Users table exists!</p>";
    } else {
        echo "<p>⚠️ Users table NOT found. Running create script...</p>";
        include 'Database/create_users_table.php';
    }
    
    // Test 3: Count users
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>✅ Total users in DB: " . $result['count'] . "</p>";
    
    // Test 4: Verify password column length
    $stmt = $conn->prepare("DESCRIBE users");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        if ($col['Field'] === 'password') {
            echo "<p>✅ Password column: " . $col['Type'] . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
