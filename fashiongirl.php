<?php require_once __DIR__ . '/model/connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VIE Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="VIE Shop - fashion vie" />
    <meta name="description" content="VIE Shop - fashion vie" />
    <meta name="keywords" content="VIE Shop - fashion vie" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" />
    <link rel="icon" type="image/png" href="/images/vie_logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- background -->
    <div class="container-fluid">
        <div id="bg">
            <?php
            // Lấy background đúng cách, không dính filter
            $bg = $conn->query("SELECT image FROM slides WHERE id=1");
            if ($row = $bg->fetch(PDO::FETCH_ASSOC)) {
                echo '<img width="100%" height="100%" src="' . $row['image'] . '" alt="">';
            }
            ?>
        </div>
    </div>
    <!-- /background -->

    <?php include("model/header.php"); ?>

    <div class="main">
        <div class="container">

            <div class="row">
                <div class="col-md-12">

                    <div class="product-main">

                        <div class="title-product-main">
                            <h3 class="section-title">Thời Trang Nữ</h3>
                        </div>

                        <!-- Lọc giá -->
                        <form method="GET" style="margin-bottom:20px;">
                            <select name="price" onchange="this.form.submit()">
                                <option value="">Lọc theo giá</option>
                                <option value="1" <?php if (isset($_GET['price']) && $_GET['price'] == '1') echo 'selected'; ?>>Dưới 100,000đ</option>
                                <option value="2" <?php if (isset($_GET['price']) && $_GET['price'] == '2') echo 'selected'; ?>>100,000đ - 200,000đ</option>
                                <option value="3" <?php if (isset($_GET['price']) && $_GET['price'] == '3') echo 'selected'; ?>>200,000đ - 500,000đ</option>
                                <option value="4" <?php if (isset($_GET['price']) && $_GET['price'] == '4') echo 'selected'; ?>>Trên 500,000đ</option>
                            </select>
                        </form>

                        <div class="content-product-main">
                            <div class="row">

                                <?php
                                // Xử lý lọc giá
                                $priceFilter = "";
                                if (isset($_GET['price'])) {
                                    $p = $_GET['price'];

                                    if ($p == "1") {
                                        $priceFilter = " AND price < 100000 ";
                                    } elseif ($p == "2") {
                                        $priceFilter = " AND price BETWEEN 100000 AND 200000 ";
                                    } elseif ($p == "3") {
                                        $priceFilter = " AND price BETWEEN 200000 AND 500000 ";
                                    } elseif ($p == "4") {
                                        $priceFilter = " AND price > 500000 ";
                                    }
                                }

                                // Lấy sản phẩm nữ + filter
                                $sql = "SELECT id,image,name,price FROM products WHERE category_id = 2";
                                $sql .= $priceFilter;

                                $result = $conn->query($sql);

                                while ($kq = $result->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <div class="col-md-3 col-sm-6 text-center">
                                        <div class="thumbnail">
                                            <div class="hoverimage1">
                                                <img src="<?php echo $kq['image']; ?>" width="100%" height="300">
                                            </div>
                                            <div class="name-product">
                                                <?php echo $kq['name']; ?>
                                            </div>
                                            <div class="price">
                                                Giá: <?php echo $kq['price'] . ' đ'; ?>
                                            </div>
                                            <div class="product-info">
                                                <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                    <button type="button" class="btn btn-primary">
                                                        <label style="color:red;">&hearts;</label> Mua hàng <label style="color:red;">&hearts;</label>
                                                    </button>
                                                </a>
                                                <a href="detail.php?id=<?php echo $kq['id']; ?>">
                                                    <button type="button" class="btn btn-primary">
                                                        <label style="color:red;">&hearts;</label> Chi Tiết <label style="color:red;">&hearts;</label>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="title-product-main">
            <h3 class="section-title">Hãng Thời Trang Nổi Tiếng</h3>
        </div>
        <?php include("model/partner.php"); ?>
    </div>

    <div class="container">
        <?php include("model/footer.php"); ?>
    </div>

    <script>
        new WOW().init();
    </script>

</body>

</html>