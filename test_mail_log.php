<?php
require 'model/mail.php';
send_mail_simple('newuser@example.com','[VIE Shop] Đăng ký thành công','<p>Chào bạn, cảm ơn đã đăng ký tại VIE Shop!</p><p>Username: demo_user</p>');
send_mail_simple('admin@localhost','[VIE Shop] Người dùng mới đăng ký','<p>Người dùng mới: demo_user (newuser@example.com)</p>');
echo "---MAIL_LOG_TEST_DONE---\n\n";
if(file_exists('logs/email.log')) {
    echo file_get_contents('logs/email.log');
} else {
    echo "Log file not found";
}
