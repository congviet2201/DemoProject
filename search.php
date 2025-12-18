<?php
// 1. Kết nối CSDL & Session
require_once __DIR__ . '/model/session.php';
require_once __DIR__ . '/model/connect.php';

// 2. Xử lý logic tìm kiếm
$keyword = "";
$products = [];
$message = "";

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);

    if (!empty($keyword)) {
        // Tìm kiếm tương đối (LIKE)
        $sql = "SELECT * FROM products WHERE name LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $searchKey = "%$keyword%";
        $stmt->bindParam(":keyword", $searchKey);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = count($products);
        if ($count == 0) {
            $message = "Không tìm thấy sản phẩm nào khớp với từ khóa: <b>" . htmlspecialchars($keyword) . "</b>";
        }
    } else {
        $message = "Vui lòng nhập tên sản phẩm để tìm kiếm.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tìm kiếm: <?= htmlspecialchars($keyword) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/vie_logo.png">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            background: #f5f5f5;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .main {
            margin-top: 20px;
            min-height: 600px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff0066;
            display: inline-block;
            padding-bottom: 5px;
            color: #333;
        }

        /* Style Card Sản phẩm */
        .thumbnail {
            border: none;
            border-radius: 10px;
            margin-bottom: 30px;
            background: #fff;
            transition: 0.3s;
            overflow: hidden;
            position: relative;
        }

        .thumbnail:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }

        .thumbnail img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: 0.5s;
        }

        .thumbnail:hover img {
            transform: scale(1.05);
        }

        .name-product {
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0 5px;
            color: #333;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            padding: 0 10px;
        }

        .price {
            font-size: 16px;
            color: #ff0066;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Nút bấm */
        .product-info {
            padding-bottom: 20px;
        }

        .btn-buy {
            background: #ff0066;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 13px;
        }

        .btn-buy:hover {
            background: #d60055;
            color: white;
        }

        .btn-detail {
            background: #5bc0de;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <?php require_once('model/header.php'); ?>

    <div class="main container">

        <div class="row">
            <div class="col-md-12">
                <h3 class="section-title">
                    Kết quả tìm kiếm: "<?= htmlspecialchars($keyword) ?>"
                </h3>
                <?php if (!empty($products)): ?>
                    <p class="text-muted">Tìm thấy <b><?= count($products) ?></b> sản phẩm.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-product-main">

            <?php if ($message != ''): ?>
                <div class="alert alert-warning text-center" style="margin-top: 20px;">
                    <i class="fa fa-search" style="font-size: 40px; margin-bottom: 10px; color: #8a6d3b;"></i><br>
                    <?= $message ?>
                    <br><br>
                    <a href="index.php" class="btn btn-default">Quay lại trang chủ</a>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($products as $kq): ?>
                    <div class="col-md-3 col-sm-6 text-center">
                        <div class="thumbnail">
                            <div class="hoverimage1">
                                <a href="Admin/detail.php?id=<?= $kq['id']; ?>">
                                    <img src="<?= $kq['image']; ?>" alt="<?= htmlspecialchars($kq['name']) ?>">
                                </a>
                            </div>

                            <div class="name-product" title="<?= htmlspecialchars($kq['name']) ?>">
                                <?= htmlspecialchars($kq['name']) ?>
                            </div>

                            <div class="price">
                                <?= number_format($kq['price']) ?> <sup>đ</sup>
                            </div>

                            <div class="product-info">
                                <a href="addcart.php?id=<?= $kq['id']; ?>">
                                    <button class="btn btn-buy">
                                        <i class="fa fa-shopping-cart"></i> Mua Ngay
                                    </button>
                                </a>
                                <a href="Admin/detail.php?id=<?= $kq['id']; ?>">
                                    <button class="btn btn-detail">
                                        <i class="fa fa-eye"></i> Xem
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <?php include("model/footer.php"); ?>

    <a href="#" class="back-to-top" style="display:none; position:fixed; bottom:25px; right:25px; background:#ff0066; color:#fff; padding:10px 15px; border-radius:50%; z-index:999;">
        <i class="fa fa-arrow-up"></i>
    </a>
    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) $('.back-to-top').fadeIn();
            else $('.back-to-top').fadeOut();
        });
        $('.back-to-top').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    </script>
</body>

</html>