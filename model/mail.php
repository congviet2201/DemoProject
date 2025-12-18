<?php
require_once __DIR__ . '/config.php';

function send_mail_simple($to, $subject, $message, $headers = []) {
    // If requested, try PHPMailer (SMTP) via Composer
    if (defined('MAIL_TRANSPORT') && MAIL_TRANSPORT === 'smtp') {
        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
                $mail->SMTPSecure = SMTP_SECURE ?: PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = SMTP_PORT;
                $mail->setFrom(FROM_EMAIL, SITE_NAME);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                return true;
            } catch (Exception $e) {
                $log = sprintf("[%s] PHPMailer ERROR TO=%s SUB=%s\nEX=%s\n%s\n\n", date('c'), $to, $subject, $e->getMessage(), $message);
                file_put_contents(MAIL_LOG_FILE, $log, FILE_APPEND);
                return false;
            }
        } else {
            // Autoload missing, write to log
            $log = sprintf("[%s] PHPMailer not installed; LOG TO=%s SUB=%s\n%s\n\n", date('c'), $to, $subject, $message);
            file_put_contents(MAIL_LOG_FILE, $log, FILE_APPEND);
            return false;
        }
    }

    // Default: fallback to PHP mail() or logging
    $defaultHeaders = [
        'From: ' . FROM_EMAIL,
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8'
    ];
    $allHeaders = array_merge($defaultHeaders, $headers);
    $headerStr = implode("\r\n", $allHeaders);

    if (MAIL_TRANSPORT === 'mail') {
        $ok = @mail($to, $subject, $message, $headerStr);
        if (!$ok) {
            $log = sprintf("[%s] MAIL FAIL TO=%s SUB=%s\n%s\n\n", date('c'), $to, $subject, $message);
            file_put_contents(MAIL_LOG_FILE, $log, FILE_APPEND);
            return false;
        }
        return true;
    }

    // Mail transport 'log' or any other
    $log = sprintf("[%s] MAIL LOG TO=%s SUB=%s\n%s\n\n", date('c'), $to, $subject, $message);
    file_put_contents(MAIL_LOG_FILE, $log, FILE_APPEND);
    return true;
}
