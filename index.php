<?php
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';
require_once __DIR__ . '/model/header.php';

$prd = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VIE Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="/images/vie_logo.png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script src="js/wow.js"></script>
    <script src="js/mylishop.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .main {
            margin-top: 20px;
        }

        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #ff0066;
            color: #fff;
            padding: 12px 16px;
            border-radius: 50%;
            display: none;
            font-size: 20px;
            z-index: 999;
            transition: 0.3s;
        }

        .back-to-top:hover {
            background: #e6005c;
            text-decoration: none;
        }

        /* Slide full-width */
        .carousel .item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .banner-container {
            margin: 30px 0;
        }

        /* Product section */
        .product-main .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff0066;
            display: inline-block;
            padding-bottom: 5px;
            color: #333;
        }

        .thumbnail {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            background: #fff;
            transition: 0.3s;
        }

        .thumbnail:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
        }

        .thumbnail img {
            transition: 0.3s;
        }

        .thumbnail:hover img {
            transform: scale(1.05);
        }

        .name-product {
            font-size: 18px;
            font-weight: 500;
            margin: 10px 0 5px;
            color: #333;
        }

        .price {
            font-size: 16px;
            color: #ff0066;
            margin-bottom: 10px;
        }

        .product-info button {
            margin: 5px 2px;
            border-radius: 20px;
        }

        .product-info button label {
            color: red;
        }
    </style>
</head>

<body>

    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- HEADER -->
    <?php //include("model/header.php"); 
    ?>

    <div class="main container">

        <!-- SLIDE -->
        <div class="row">
            <div class="col-md-12">
                <?php include("model/slide.php"); ?>
            </div>
        </div>

        <!-- BANNER -->
        <div class="row banner-container">
            <div class="col-md-12">
                <?php include("model/banner.php"); ?>
            </div>
        </div>

        <!-- SẢN PHẨM -->
        <div class="product-main">

            <?php
            // Danh sách các danh mục để hiển thị sản phẩm
            $categories = [
                ['name' => 'Sản phẩm mới', 'category_id' => 3, 'limit' => null],
                ['name' => 'Thời Trang Nam', 'category_id' => 1, 'limit' => 8],
                ['name' => 'Thời Trang Nữ', 'category_id' => 2, 'limit' => 8],
            ];

            foreach ($categories as $cat) :
                $sql = "SELECT id, image, name, price FROM products WHERE category_id = :catid";
                if ($cat['limit']) $sql .= " LIMIT " . intval($cat['limit']);
                $stmt = $conn->prepare($sql);
                $stmt->execute([':catid' => $cat['category_id']]);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <div class="title-product-main">
                    <h3 class="section-title"><?php echo $cat['name']; ?></h3>
                </div>
                <div class="content-product-main">
                    <div class="row">
                        <?php foreach ($products as $kq) : ?>
                            <div class="col-md-3 col-sm-6 text-center">
                                <div class="thumbnail">
                                    <div class="hoverimage1">
                                        <img src="<?php echo $kq['image']; ?>" alt="<?php echo $kq['name']; ?>" height="300">
                                    </div>
                                    <div class="name-product"><?php echo $kq['name']; ?></div>
                                    <div class="price">Giá: <?php echo number_format($kq['price']); ?> <sup>đ</sup></div>
                                    <div class="product-info">
                                        <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                            <button class="btn btn-primary">
                                                <label>&hearts;</label> Mua hàng <label>&hearts;</label>
                                            </button>
                                        </a>
                                        <a href="Admin/detail.php?id=<?php echo $kq['id']; ?>">
                                            <button class="btn btn-info">
                                                <label>&hearts;</label> Chi Tiết <label>&hearts;</label>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- nhãn hàng -->
        <div>
            <?php include("model/partner.php"); ?>

        </div>
    </div>

    <script>
        // Hiện nút back-to-top
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        $('.back-to-top').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    </script>
    <?php include("model/footer.php"); ?>

</body>

</html>