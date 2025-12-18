<?php
// Site-wide configuration
define('SITE_NAME', 'VIE Shop');
define('FROM_EMAIL', 'no-reply@localhost');
define('ADMIN_EMAIL', 'admin@localhost');

// Mail transport options:
//  - 'mail' : PHP mail()
//  - 'log'  : write messages to logs/email.log
//  - 'smtp' : use PHPMailer (recommended) via Composer (phpmailer/phpmailer)
define('MAIL_TRANSPORT', 'log'); // changed to 'log' for local development; use 'smtp' when PHPMailer installed

// Log file for email fallbacks
define('MAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

// Ensure logs dir exists
if (!is_dir(__DIR__ . '/../logs')) {
    @mkdir(__DIR__ . '/../logs', 0755, true);
}

// SMTP / PHPMailer settings (used when MAIL_TRANSPORT === 'smtp')
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.example.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: 'username');
define('SMTP_PASS', getenv('SMTP_PASS') ?: 'password');
define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'tls'); // 'tls' or 'ssl' or ''
// Optional: set MAIL_TRANSPORT to 'log' in local dev to avoid sending real mail
