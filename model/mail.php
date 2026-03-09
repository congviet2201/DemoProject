<?php
require_once __DIR__ . '/config.php';

function send_mail($to, $subject, $html)
{
    // SMTP
    if (MAIL_TRANSPORT === 'smtp') {
        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (!file_exists($autoload)) {
            log_mail($to, $subject, $html, 'Composer not installed');
            return false;
        }

        require_once $autoload;

        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE === 'ssl'
                ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom(FROM_EMAIL, SITE_NAME);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            log_mail($to, $subject, $html, $e->getMessage());
            return false;
        }
    }

    // LOG fallback
    log_mail($to, $subject, $html, 'LOG MODE');
    return true;
}

function log_mail($to, $subject, $content, $error)
{
    $log = sprintf(
        "[%s]\nTO: %s\nSUBJECT: %s\nERROR: %s\nCONTENT:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $to,
        $subject,
        $error,
        $content
    );
    file_put_contents(MAIL_LOG_FILE, $log, FILE_APPEND);
}
