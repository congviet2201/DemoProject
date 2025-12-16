<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
    .navbar {
        background: #ffffff;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        padding: 8px 0;
        font-size: 15px;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 26px !important;
        color: #ff2d75 !important;
        letter-spacing: 1px;
    }

    .navbar-nav>li>a {
        font-weight: 600;
        padding: 12px 18px;
        color: #333 !important;
    }

    .navbar-nav>li>a:hover {
        color: #ff0066 !important;
    }

    /* Bộ lọc dropdown */
    .navbar-form select {
        border: 1px solid #ddd;
        padding: 7px 10px;
        font-weight: 600;
        color: #444;
        border-radius: 6px;
    }

    .navbar-form select:hover {
        border-color: #ff2d75;
    }

    /* SEARCH BAR */
    .header-search input {
        border-radius: 20px 0 0 20px !important;
        border: 1px solid #ccc;
        padding-left: 15px;
    }

    .header-search input:focus {
        border-color: #ff2d75;
        box-shadow: none;
    }

    .btn-search {
        background: #ff2d75;
        color: #fff;
        border-radius: 0 20px 20px 0 !important;
        border: none;
    }

    .btn-search:hover {
        background: #e60059;
    }

    /* CART BUTTON */
    .header-cart {
        background: #ff2d75;
        border: none;
        color: #fff;
        border-radius: 20px;
        padding: 8px 16px;
        font-weight: bold;
        transition: 0.25s;
    }

    .header-cart:hover {
        background: #e60059;
    }

    /* Dropdown user */
    .navbar-nav .dropdown-menu {
        min-width: 180px;
        padding: 0;
        border-radius: 6px;
    }

    .dropdown-menu>li>a {
        padding: 10px 15px;
        font-weight: 600;
    }

    /* Icon-spacing */
    .fa {
        margin-right: 5px;
    }

    /* Mobile optimization */
    @media (max-width: 768px) {
        .navbar-nav>li>a {
            padding: 10px 15px;
        }

        .header-cart {
            width: 100%;
            margin-top: 10px;
        }

        .navbar-form {
            width: 100%;
        }
    }
</style>

<header>
    <nav class="navbar navbar-default" style="border-radius:0; margin-bottom: 0;">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="index.php">
                    MyLiShop
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-main">

                <ul class="nav navbar-nav">
                    <li><a href="index.php">Trang Chủ</a></li>
                    <li><a href="introduceshop.php">Dịch Vụ</a></li>
                    <li><a href="model/lienhe.php">Liên Hệ</a></li>
                </ul>

                <!-- BỘ LỌC DUY NHẤT -->
                <form class="navbar-form navbar-left" action="" method="GET" style="margin-left: 18px;">
                    <select name="filter" class="form-control"
                        onchange="window.location.href=this.value;"
                        style="font-weight:600; min-width:160px;">

                        <option value="">-- Chọn loại sản phẩm --</option>

                        <option value="fashionboy.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "fashionboy.php" ? "selected" : "") ?>>
                            Thời Trang Nam
                        </option>

                        <option value="fashiongirl.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "fashiongirl.php" ? "selected" : "") ?>>
                            Thời Trang Nữ
                        </option>

                        <option value="newproduct.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "newproduct.php" ? "selected" : "") ?>>
                            Hàng Mới Về
                        </option>

                    </select>
                </form>

                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <form class="navbar-form" action="search.php" method="GET">
                            <div class="input-group header-search">
                                <input type="text" class="form-control" placeholder="Tìm sản phẩm..." name="keyword" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-search" type="submit">
                                        <span class="fa fa-search"></span>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </li>

                    <li class="cart-total">
                        <a href="cart.php">
                            <button type="button" class="btn header-cart">
                                <span class="fa fa-shopping-cart"></span>
                                <span id="cart-total">
                                    <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                                </span> sản phẩm
                            </button>
                        </a>
                    </li>

                    <?php if (!isset($_SESSION['user'])): ?>
                        <li><a href="user/login.php" style="font-weight:600;">Đăng nhập</a></li>
                        <li><a href="user/register.php" style="font-weight:600;">Đăng ký</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-weight:600;">
                                <i class="fa fa-user"></i> Chào, <?= $_SESSION['user']['fullname']; ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a href="user/profile.php"><i class="fa fa-user-circle-o"></i> Thông tin cá nhân</a></li>
                                <li class="divider"></li>
                                <li><a href="user/logout.php" style="color:red;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                </ul>

            </div>
        </div>
    </nav>
</header>