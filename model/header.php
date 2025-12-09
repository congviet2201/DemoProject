<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="index.php" style="font-weight:bold; color:#ff0066; font-size:24px;">
                    MyLiShop
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-main">

                <ul class="nav navbar-nav">
                    <li><a href="index.php">Trang Chủ</a></li>
                    <li><a href="introduceshop.php">Dịch Vụ</a></li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Sản Phẩm <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="fashionboy.php"><i class="fa fa-male"></i> Thời Trang Nam</a></li>
                            <li class="divider"></li>
                            <li><a href="fashiongirl.php"><i class="fa fa-female"></i> Thời Trang Nữ</a></li>
                            <li class="divider"></li>
                            <li><a href="newproduct.php"><i class="fa fa-star"></i> Hàng Mới Về</a></li>
                        </ul>
                    </li>

                    <li><a href="model/lienhe.php">Liên Hệ</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <form class="navbar-form" action="search.php" method="GET">
                            <div class="input-group header-search">
                                <input type="text" class="form-control" placeholder="Tìm sản phẩm..." maxlength="50" name="keyword" required>
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
                                <span class="fa fa-shopping-cart"></span>&nbsp;
                                <span id="cart-total">
                                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                                </span>
                                sản phẩm
                            </button>
                        </a>
                    </li>


                    <?php if (!isset($_SESSION['user'])): ?>
                        <li><a href="user/login.php" style="font-weight: 600;">Đăng nhập</a></li>
                        <li><a href="user/register.php" style="font-weight: 600;">Đăng ký</a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-weight: 600;">
                                <i class="fa fa-user"></i> Chào, <?= $_SESSION['user']['fullname']; ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="user/profile.php"><i class="fa fa-user-circle-o"></i> Thông tin cá nhân</a></li>
                                <li class="divider"></li>
                                <li><a href="user/logout.php" style="color: #d9534f;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

            </div>
        </div>
    </nav>
</header>

<style>
    /* --- CÀI ĐẶT CHUNG (BODY VÀ FONT) --- */
    body {
        /* Đảm bảo font chữ sạch sẽ và dễ đọc */
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    /* --- THANH ĐIỀU HƯỚNG CHÍNH (NAVBAR) --- */
    .navbar {
        /* Nền trắng, viền hồng nổi bật */
        background-color: #fff;
        border: none;
        border-bottom: 3px solid #ff0066;
        margin-bottom: 0;
        border-radius: 0;
        padding: 10px 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        /* Thêm bóng mờ */
        z-index: 1030;
        /* Tăng z-index để nổi bật hơn */
    }

    /* Logo thương hiệu */
    .navbar-brand {
        font-weight: bold;
        transition: color 0.3s;
    }

    .navbar-brand:hover {
        color: #cc0052 !important;
        /* Màu đậm hơn khi hover */
    }

    /* --- LIÊN KẾT CHÍNH TRÊN THANH NAV --- */
    .navbar-nav>li>a {
        font-size: 15px;
        font-weight: 500;
        color: #333 !important;
        padding: 10px 15px;
        transition: all 0.3s ease-in-out;
        border-radius: 5px;
        /* Bo góc nhẹ */
    }

    .navbar-nav>li>a:hover,
    .navbar-nav>li.active>a {
        color: #ff0066 !important;
        background-color: #fff2f7;
        /* Nền hồng nhạt khi hover */
    }

    /* --- CẤU HÌNH DROPDOWN MƯỢT MÀ --- */
    .dropdown-menu {
        border: 1px solid #ff0066;
        padding: 5px 0;
        /* Giảm padding trên/dưới */
        min-width: 220px;
        /* Tăng chiều rộng menu */
        box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        border-radius: 6px;
    }

    .dropdown-menu>li>a {
        padding: 10px 20px;
        transition: background-color 0.3s, color 0.3s;
        color: #333;
        font-size: 14px;
    }

    .dropdown-menu>li>a:hover {
        background-color: #ff0066;
        color: #fff !important;
        padding-left: 25px;
        /* Thêm hiệu ứng trượt nhẹ */
    }

    .divider {
        margin: 5px 0;
        /* Giữ khoảng cách nhẹ */
        border-color: #ffe5ee;
    }

    /* Hiệu ứng hover cho dropdown */
    @media (min-width: 769px) {
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
            animation: fadeIn 0.3s ease-in-out;
        }
    }

    /* Hiệu ứng hiện dần */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
            /* Hiện từ trên xuống */
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* --- TÌM KIẾM VÀ GIỎ HÀNG (NAVBAR-RIGHT) --- */

    /* Thanh tìm kiếm */
    .header-search input {
        border-radius: 20px 0 0 20px;
        border: 1px solid #ffccdd;
        /* Viền hồng nhạt */
        width: 220px !important;
        /* Tăng chiều rộng */
        transition: border-color 0.3s;
    }

    .header-search input:focus {
        border-color: #ff0066;
        /* Viền hồng đậm khi focus */
        box-shadow: none;
    }

    .header-search .btn-search {
        border-radius: 0 20px 20px 0;
        background-color: #fff;
        border: 1px solid #ffccdd;
        border-left: none;
        color: #ff0066;
        transition: all 0.3s;
    }

    .header-search .btn-search:hover {
        background: #ff0066;
        color: white;
        border-color: #ff0066;
    }

    /* Nút Giỏ hàng */
    .header-cart {
        background-color: #ff0066;
        color: #fff;
        border-radius: 25px;
        border: none;
        padding: 6px 18px;
        /* Tăng padding */
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
        box-shadow: 0 2px 5px rgba(255, 0, 102, 0.4);
        /* Bóng nhẹ */
    }

    .header-cart:hover {
        background-color: #cc0052;
        /* Màu đậm hơn */
        transform: translateY(-1px);
        /* Hiệu ứng nhấn */
    }

    /* Tổng sản phẩm trong giỏ hàng */
    .cart-total a {
        /* Bỏ gạch chân khi hover cho liên kết giỏ hàng */
        text-decoration: none !important;
    }

    #cart-total {
        margin-right: 5px;
        background-color: white;
        color: #ff0066;
        padding: 2px 7px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: bold;
    }

    /* Thông tin người dùng đăng nhập */
    .navbar-text {
        font-size: 15px;
        color: #333;
        margin-top: 15px;
        margin-bottom: 15px;
        padding-right: 5px;
    }

    .navbar-text b {
        color: #ff0066;
        font-weight: 700;
    }

    /* Nút Đăng xuất */
    .navbar-right li a[href*="logout.php"] {
        color: #d9534f !important;
        /* Màu đỏ */
    }

    .navbar-right li a[href*="logout.php"]:hover {
        color: #cc0000 !important;
        background-color: #ffeeee;
    }

    /* Tùy chỉnh cho thiết bị di động */
    @media (max-width: 768px) {
        .navbar-nav {
            margin: 7.5px -15px;
        }

        .navbar-nav .header-search {
            margin-left: 15px;
            margin-right: 15px;
        }

        .header-search input {
            width: 100% !important;
        }

        .header-search .btn-search {
            border-left: 1px solid #ffccdd;
            /* Fix viền */
        }

        .navbar-right {
            margin-top: 10px;
        }
    }
</style>