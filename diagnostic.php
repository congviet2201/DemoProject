<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';

echo "<h1>✅ VIE Shop - Complete Diagnostic Report</h1>";
echo "<hr>";

$issues = [];
$warnings = [];
$passed = [];

// TEST 1: Session Configuration
echo "<h2>🔹 Session Configuration</h2>";
try {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $passed[] = "Session is active";
        echo "✅ Session is active (ID: " . session_id() . ")<br>";
    }
    
    if (ini_get('session.gc_maxlifetime')) {
        $gc_time = intval(ini_get('session.gc_maxlifetime'));
        echo "✅ GC maxlifetime: $gc_time seconds (" . ($gc_time / 86400) . " days)<br>";
        if ($gc_time >= 2592000) { // 30 days
            $passed[] = "Session GC lifetime is 30+ days";
        }
    }
} catch (Exception $e) {
    $issues[] = "Session error: " . $e->getMessage();
}

echo "<hr>";

// TEST 2: Database Connection
echo "<h2>🔹 Database Connection</h2>";
try {
    $stmt = $conn->prepare("SELECT 1");
    $stmt->execute();
    $passed[] = "Database connected successfully";
    echo "✅ Database connection successful<br>";
} catch (PDOException $e) {
    $issues[] = "Database connection failed: " . $e->getMessage();
    echo "❌ " . $e->getMessage() . "<br>";
}

echo "<hr>";

// TEST 3: Users Table
echo "<h2>🔹 Users Table Structure</h2>";
try {
    $stmt = $conn->prepare("DESCRIBE users");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $required_fields = ['id', 'fullname', 'username', 'email', 'phone', 'address', 'password', 'created_at'];
    $found_fields = array_column($columns, 'Field');
    
    foreach ($required_fields as $field) {
        if (in_array($field, $found_fields)) {
            echo "✅ Column: $field<br>";
        } else {
            $issues[] = "Missing column: $field";
            echo "❌ Missing column: $field<br>";
        }
    }
    
    // Check password column length
    foreach ($columns as $col) {
        if ($col['Field'] === 'password') {
            echo "   Type: " . $col['Type'] . "<br>";
            if (strpos($col['Type'], '255') !== false) {
                $passed[] = "Password column is VARCHAR(255)";
            }
        }
    }
    
    $passed[] = "Users table structure verified";
} catch (PDOException $e) {
    $issues[] = "Users table check failed: " . $e->getMessage();
}

echo "<hr>";

// TEST 4: Cart & Orders Session Arrays
echo "<h2>🔹 Session Arrays Initialization</h2>";
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $passed[] = "Cart array initialized";
    echo "✅ Cart array exists (count: " . count($_SESSION['cart']) . ")<br>";
} else {
    $warnings[] = "Cart array not initialized";
    echo "⚠️ Cart array not initialized<br>";
}

if (isset($_SESSION['orders']) && is_array($_SESSION['orders'])) {
    $passed[] = "Orders array initialized";
    echo "✅ Orders array exists (count: " . count($_SESSION['orders']) . ")<br>";
} else {
    $warnings[] = "Orders array not initialized";
    echo "⚠️ Orders array not initialized<br>";
}

echo "<hr>";

// TEST 5: Key Files Exist
echo "<h2>🔹 Required Files</h2>";
$required_files = [
    'model/session.php',
    'model/connect.php',
    'model/header.php',
    'user/login.php',
    'user/login-back.php',
    'user/register.php',
    'user/register-back.php',
    'cart.php',
    'checkout.php',
    'process_checkout.php',
    'index.php'
];

foreach ($required_files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ $file<br>";
        $passed[] = "$file exists";
    } else {
        echo "❌ $file NOT FOUND<br>";
        $issues[] = "Missing file: $file";
    }
}

echo "<hr>";

// TEST 6: Authentication Logic Test
echo "<h2>🔹 Authentication Logic</h2>";
try {
    // Create test user
    $test_username = 'diagnostic_test_' . time();
    $test_email = 'diag_' . time() . '@test.local';
    $test_pass = 'TestPass123!';
    $hash = password_hash($test_pass, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['Diagnostic User', $test_username, $test_email, '0123456789', 'Test', $hash]);
    echo "✅ Test user created<br>";
    
    // Verify password
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$test_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (password_verify($test_pass, $user['password'])) {
        $passed[] = "Password hashing and verification working";
        echo "✅ Password verification successful<br>";
    } else {
        $issues[] = "Password verification failed";
        echo "❌ Password verification failed<br>";
    }
    
    // Clean up
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$test_username]);
    
} catch (PDOException $e) {
    $warnings[] = "Auth test: " . $e->getMessage();
    echo "⚠️ Auth test warning: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// SUMMARY
echo "<h2>📊 Test Summary</h2>";
echo "<p><strong>✅ Passed:</strong> " . count($passed) . " checks</p>";
echo "<p><strong>⚠️ Warnings:</strong> " . count($warnings) . " notices</p>";
echo "<p><strong>❌ Issues:</strong> " . count($issues) . " problems</p>";

if (count($issues) === 0) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3>🎉 All Systems Operational!</h3>";
    echo "<p>Your VIE Shop installation is configured correctly. You can now:</p>";
    echo "<ul>";
    echo "<li><a href='/user/register.php'>Register a new account</a></li>";
    echo "<li><a href='/user/login.php'>Log in to your account</a></li>";
    echo "<li><a href='/index.php'>Browse products</a></li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3>⚠️ Please fix the following issues:</h3>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li>" . $issue . "</li>";
    }
    echo "</ul>";
    echo "</div>";
}
?>
