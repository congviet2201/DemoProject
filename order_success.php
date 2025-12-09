<?php
// File: order_success.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('model/header.php');
?>
<div class="main container text-center" style="padding: 100px 0;">
    <h2 style="color: green;"><i class="fa fa-check-circle"></i> ĐẶT HÀNG THÀNH CÔNG</h2>
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-success" style="font-size: 1.2em;"><?php echo $_SESSION['flash_message']; ?></div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>
    <p>Cảm ơn bạn đã tin tưởng và mua hàng tại MyLiShop. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất!</p>
    <a href="index.php" class="btn btn-primary btn-lg" style="margin-top: 20px;">Tiếp tục mua sắm</a>
</div>
<?php require_once('model/footer.php'); ?>