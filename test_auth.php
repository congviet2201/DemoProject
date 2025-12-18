<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';

echo "<h2>🔐 Authentication Flow Test</h2>";
echo "<hr>";

// Test 1: Register a new test user
echo "<h3>Test 1: Register new user</h3>";
$test_user = [
    'fullname' => 'Test User ' . date('YmdHis'),
    'username' => 'testuser' . rand(1000, 9999),
    'email' => 'test' . rand(1000, 9999) . '@test.com',
    'phone' => '0123456789',
    'address' => 'Test Address'
];
$password = 'testpass123';

try {
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users(fullname, username, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$test_user['fullname'], $test_user['username'], $test_user['email'], $test_user['phone'], $test_user['address'], $hashPass]);
    echo "✅ User registered: " . htmlspecialchars($test_user['username']) . "<br>";
    echo "   Email: " . htmlspecialchars($test_user['email']) . "<br>";
} catch (PDOException $e) {
    echo "❌ Registration failed: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 2: Login verification
echo "<h3>Test 2: Login verification</h3>";
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test_user['username']]);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            echo "✅ Password verification passed!<br>";
            echo "   User ID: " . $user['id'] . "<br>";
            echo "   Username: " . $user['username'] . "<br>";
            echo "   Email: " . $user['email'] . "<br>";
            
            // Simulate setting session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'fullname' => $user['fullname'],
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'address' => $user['address']
            ];
            echo "✅ Session would be set with user data<br>";
        } else {
            echo "❌ Password verification failed!<br>";
        }
    } else {
        echo "❌ User not found!<br>";
    }
} catch (PDOException $e) {
    echo "❌ Login test failed: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 3: Session persistence
echo "<h3>Test 3: Session check</h3>";
if (isset($_SESSION['user'])) {
    echo "✅ User is logged in!<br>";
    echo "   Session user: " . $_SESSION['user']['username'] . "<br>";
    echo "   Cookie name: " . session_name() . "<br>";
    echo "   Session ID: " . session_id() . "<br>";
} else {
    echo "ℹ️ No user in session (normal for new sessions)<br>";
}

echo "<hr>";
echo "<p><strong>All tests completed!</strong></p>";
?>
