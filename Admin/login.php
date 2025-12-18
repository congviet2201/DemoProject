<?php
require_once __DIR__ . '/../model/session.php';
// Simple admin login form
// Default password is 'admin123' — you can change in login-back.php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body style="padding:30px;">
<div class="container" style="max-width:480px; margin:0 auto;">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Admin Login</h3></div>
        <div class="panel-body">
            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <form method="post" action="login-back.php">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="../index.php" class="btn btn-default">Home</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>