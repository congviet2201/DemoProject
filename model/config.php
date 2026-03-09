<?php
define('SITE_NAME', 'VIE Shop');
define('SITE_URL', 'http://localhost/vieshop');

define('FROM_EMAIL', 'yourgmail@gmail.com');
define('ADMIN_EMAIL', 'admin@gmail.com');

// mail | log | smtp
define('MAIL_TRANSPORT', 'smtp');

// Log
define('MAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

// SMTP Gmail
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'yourgmail@gmail.com');
define('SMTP_PASS', 'APP_PASSWORD_GMAIL');
define('SMTP_SECURE', 'tls');
