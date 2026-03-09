<?php
function mail_verify_account($email, $token)
{
    $link = SITE_URL . "/verify.php?token=$token";

    $html = "
        <h2>Welcome to " . SITE_NAME . "</h2>
        <p>Click the link below to verify your account:</p>
        <p><a href='$link'>Verify Account</a></p>
    ";

    return send_mail($email, 'Verify your account', $html);
}
