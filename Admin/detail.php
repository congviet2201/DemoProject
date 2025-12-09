<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="description" content="Fashion MyLiShop - fashion mylishop" />
    <meta name="keywords" content="Fashion MyLiShop - fashion mylishop" />

    <link rel="icon" type="image/png" href="images/logohong.png">

    <link rel="stylesheet" type="text/css" href="../admin/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../dist/css/sb-admin-2.css">
    <link rel="stylesheet" href="../dist/css/timeline.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>

    <style>
        .price-old {
            text-decoration: line-through;
            color: #999;
            font-size: 1.1em;
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
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .btn-order-custom:hover {
            background-color: #cc0052;
            color: #fff;
        }
    </style>

</head>

<body>
    <a href="/index.php" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <?php
    require_once('../model/header.php');
    require_once('../model/connect.php');
    error_reporting(2);

    // BẢO VỆ: Kiểm tra id có tồn tại hay không
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID sản phẩm không hợp lệ!");
    }

    $id = intval($_GET['id']);
    // Đảm bảo kết nối PDO có sẵn (Nếu bạn không dùng connect.php, hãy bỏ dòng này)
    if (!isset($conn) || !$conn) {
        // Thông báo lỗi nếu kết nối không thành công
        echo '<div class="container"><div class="alert alert-danger" style="margin-top: 20px;">Không thể kết nối với Database.</div></div>';
        exit;
    }

    $sql = "SELECT * FROM products WHERE id = :id";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Lỗi truy vấn dữ liệu: ' . $e->getMessage());
    }


    if ($row) {
        $thum_Image = ($row['image'] == '' ? 'images/no-image.jpg' : $row['image']); // Ảnh mặc định
    ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="detail-product">
                        <div class="row">

                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <div class="product-frame">
                                    <div style="margin-bottom: 20px; margin-top: 10px;">
                                        <img src="../<?php echo $thum_Image; ?>" width="100%" height="450" class="img-responsive" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 col-xs-6 col-xs-12">
                                <h2><?php echo $row['name']; ?></h2>
                                <hr>

                                <?php
                                $price = $row['price'];
                                $saleprice_percent = $row['saleprice']; // Giả định đây là % giảm giá (ví dụ: 10, 20)
                                $formatted_price = number_format($price, 0, ',', '.') . ' VNĐ';

                                if ($saleprice_percent > 0) {
                                    $new_price = $price * (1 - $saleprice_percent / 100);
                                    $formatted_new_price = number_format($new_price, 0, ',', '.') . ' VNĐ';
                                ?>
                                    <p class="price price-old">Giá cũ: <?php echo $formatted_price; ?></p>
                                    <p class="price price-new">Giá ưu đãi: <?php echo $formatted_new_price; ?></p>
                                <?php
                                } else {
                                ?>
                                    <p class="price price-new">Giá sản phẩm: <?php echo $formatted_price; ?></p>
                                <?php
                                }
                                ?>

                                <hr>

                                <div class="button-order">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="/addcart.php?id=<?php echo $row['id']; ?>">
                                                <button class="btn btn-warning btn-md btn-order-custom">
                                                    <i class="fa fa-shopping-cart"></i> Đặt mua ngay
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <p style="padding-top: 30px;">
                                    <span class="fa fa-check-circle" style="color: #ff0066;"></span> GIAO HÀNG TOÀN QUỐC<br>
                                    <span class="fa fa-check-circle" style="color: #ff0066;"></span> THANH TOÁN KHI NHẬN HÀNG<br>
                                    <span class="fa fa-check-circle" style="color: #ff0066;"></span> ĐỔI HÀNG TRONG 15 NGÀY
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        echo '<div class="container"><div class="alert alert-warning" style="margin-top: 20px;">Không tìm thấy sản phẩm này.</div></div>';
    }
    ?>

    <?php include('../model/footer.php'); ?>

    <script>
        new WOW().init();
    </script>

</body>

</html>