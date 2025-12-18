<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VIE Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/vie_logo.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../dist/css/sb-admin-2.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .price-old {
            text-decoration: line-through;
            color: #999;
        }

        .price-new {
            color: #ff0066;
            font-size: 1.6em;
            font-weight: bold;
        }

        .btn-order-custom {
            background-color: #ff0066;
            border-color: #cc0052;
            color: #fff;
            font-weight: 600;
            padding: 10px 30px;
            transition: 0.3s;
        }

        .btn-order-custom:hover {
            background-color: #cc0052;
        }
    </style>
</head>

<body>

    <?php
    require_once('../model/header.php');
    require_once('../model/connect.php');
    error_reporting(2);

    // BẢO VỆ ID
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID sản phẩm không hợp lệ!");
    }

    $id = intval($_GET['id']);

    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo '<div class="container"><div class="alert alert-warning">Không tìm thấy sản phẩm này.</div></div>';
        exit;
    }

    $thum_Image = ($row['image'] == '' ? 'images/no-image.jpg' : $row['image']);
    ?>

    <div class="container" style="margin-top: 20px;">

        <!-- ====== CRUD BUTTONS ====== -->
        <a href="product-edit.php?idProduct=<?php echo $row['id']; ?>" class="btn btn-primary">
            <i class="glyphicon glyphicon-edit"></i> Sửa sản phẩm
        </a>

        <a href="product-delete.php?id=<?php echo $row['id']; ?>"
            class="btn btn-danger"
            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?');">
            <i class="glyphicon glyphicon-trash"></i> Xóa sản phẩm
        </a>
    </div>

    <!-- ====== PRODUCT DETAIL ====== -->
    <div class="row">
        <div class="col-md-5">
            <img src="../<?php echo $thum_Image; ?>" width="100%" class="img-responsive" style="object-fit: cover;">
        </div>

        <div class="col-md-7">
            <h2><?php echo $row['name']; ?></h2>
            <hr>

            <?php
            $price = $row['price'];
            $sale = $row['saleprice'];

            if ($sale > 0) {
                $new_price = $price * (1 - $sale / 100);
                echo "<p class='price-old'>Giá cũ: " . number_format($price) . " VNĐ</p>";
                echo "<p class='price-new'>Giá ưu đãi: " . number_format($new_price) . " VNĐ</p>";
            } else {
                echo "<p class='price-new'>Giá: " . number_format($price) . " VNĐ</p>";
            }
            ?>

            <hr>

            <a href="../addcart.php?id=<?php echo $row['id']; ?>">
                <button class="btn btn-warning btn-order-custom">
                    <i class="glyphicon glyphicon-shopping-cart"></i> Đặt mua ngay
                </button>
            </a>

            <p style="margin-top: 25px;">
                <span class="glyphicon glyphicon-ok" style="color:#ff0066"></span> GIAO HÀNG TOÀN QUỐC<br>
                <span class="glyphicon glyphicon-ok" style="color:#ff0066"></span> THANH TOÁN KHI NHẬN HÀNG<br>
                <span class="glyphicon glyphicon-ok" style="color:#ff0066"></span> ĐỔI HÀNG TRONG 15 NGÀY
            </p>
        </div>
    </div>

    </div>

    <?php include('../model/footer.php'); ?>

</body>

</html>