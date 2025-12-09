<?php
session_start();
require_once('model/connect.php');
require_once('model/header.php'); // Giả định file này include <head> và header HTML
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Giỏ Hàng | Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logohong.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-table {
            /* Thêm CSS giỏ hàng */
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* ... các style khác ... */
    </style>
</head>

<body>
    <div class="main container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center" style="color: #ff0066; margin-bottom: 30px;"><i class="fa fa-shopping-cart"></i> Giỏ Hàng Của Bạn</h2>

                <?php
                if (isset($_SESSION['flash_message'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['flash_message'] . '</div>';
                    unset($_SESSION['flash_message']);
                }
                ?>

                <?php if (empty($_SESSION['cart'])): ?>
                    <div class="alert alert-info text-center">
                        Giỏ hàng của bạn đang trống! <a href="index.php" class="alert-link">Tiếp tục mua sắm</a>.
                    </div>
                <?php else:
                    $total_cart_price = 0;
                ?>
                    <form action="update-cart.php" method="POST">
                        <div class="cart-table">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $product_id => $item) :
                                        $sub_total = $item['price'] * $item['quantity'];
                                        $total_cart_price += $sub_total;
                                    ?>
                                        <tr>
                                            <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-image" style="width: 80px; height: 80px; object-fit: cover;"></td>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo number_format($item['price']); ?> <sup>đ</sup></td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 60px; text-align: center;">
                                            </td>
                                            <td><?php echo number_format($sub_total); ?> <sup>đ</sup></td>
                                            <td>
                                                <a href="update-cart.php?action=delete&id=<?php echo $product_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                    <i class="fa fa-times"></i> Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-6">
                                <a href="index.php" class="btn btn-default btn-update-cart"><i class="fa fa-chevron-left"></i> Tiếp tục mua sắm</a>
                                <button type="submit" name="update_cart" class="btn btn-primary btn-update-cart"><i class="fa fa-refresh"></i> Cập nhật giỏ hàng</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <h3>Tổng cộng: <span class="total-price" style="font-size: 20px; font-weight: bold; color: #ff0066;"><?php echo number_format($total_cart_price); ?> <sup>đ</sup></span></h3>
                                <a href="checkout.php" class="btn btn-success btn-lg" style="border-radius: 20px; font-weight: 600;"><i class="fa fa-check-circle"></i> Thanh toán</a>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include("model/footer.php"); ?>

</body>

</html>