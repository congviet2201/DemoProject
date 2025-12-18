<?php require_once __DIR__ . '/session.php'; ?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
    :root {
        --accent: #ff2d75;
        --accent-dark: #e60059;
        --muted: #666;
        --bg: #ffffff;
        --nav-height: 64px;
    }
    /* Header layout */
    .navbar {
        background: linear-gradient(180deg, #fff 0%, #fff 60%);
        border: none;
        padding: 8px 20px;
        font-size: 15px;
        min-height: var(--nav-height);
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }

    .navbar .container-fluid {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .navbar-header { display:flex; align-items:center; }

    .navbar-brand{
        display:flex;
        align-items:center;
        gap:12px;
        font-weight:800;
        font-size:22px;
        color:var(--accent);
        letter-spacing:0.4px;
        margin-right:8px;
    }
    .navbar-brand img{ height:42px; width:auto; display:block; }

    /* Primary nav links */
    .nav>li>a{ color:#333; font-weight:600; padding:12px 14px; transition:all .12s ease; }
    .nav>li>a:hover{ color:var(--accent-dark); background:rgba(255,45,117,0.06); border-radius:6px; }

    /* Filter dropdown */
    .navbar-form select{ border:1px solid #eee; padding:8px 12px; font-weight:600; color:#444; border-radius:8px; background:#fafafa; }

    /* Search */
    .header-search{ display:flex; align-items:center; }
    .header-search input{ border-radius:26px 0 0 26px; border:1px solid #eee; padding:8px 12px; width:220px; transition:box-shadow .12s ease, border-color .12s ease; }
    .header-search input:focus{ outline:none; box-shadow:0 6px 20px rgba(230,45,117,0.08); border-color:var(--accent); }
    .btn-search{ background:linear-gradient(90deg,var(--accent),var(--accent-dark)); color:#fff; border-radius:0 26px 26px 0; border:none; padding:8px 12px; }

    /* Cart */
    .header-cart{ background:transparent; border:1px solid rgba(0,0,0,0.06); color:#222; border-radius:22px; padding:8px 12px; font-weight:700; display:flex; align-items:center; gap:8px; }
    .header-cart .badge{ background:var(--accent); color:#fff; border-radius:10px; padding:3px 7px; font-size:12px; }

    /* Dropdown user */
    .navbar-nav .dropdown-menu{ min-width:220px; border-radius:10px; padding:6px 0; box-shadow:0 12px 36px rgba(0,0,0,0.12); }
    .dropdown-menu>li>a{ padding:10px 18px; font-weight:600; color:#333; }

    /* Small icon tweaks */
    .fa{ margin-right:8px; color:var(--muted); }

    /* Responsive adjustments */
    @media (max-width:991px){
        .header-search input{ width:160px; }
        .nav>li>a{ padding:10px 12px; }
    }
    @media (max-width:767px){
        .navbar .container-fluid{ flex-direction:column; align-items:stretch; gap:8px; }
        .navbar-header{ width:100%; justify-content:space-between; }
        .navbar-form{ width:100%; margin:6px 0; display:flex; justify-content:center; }
        .header-search{ width:100%; justify-content:center; }
        .header-search input{ width:70%; }
        .cart-total{ margin-top:6px; }
        .nav.navbar-nav{ margin-top:6px; }
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

                <a class="navbar-brand" href="/index.php">
                    VIE Shop
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-main">

                <ul class="nav navbar-nav">
                    <li><a href="/index.php">Trang Chủ</a></li>
                    <li><a href="/introduceshop.php">Dịch Vụ</a></li>
                    <li><a href="/model/lienhe.php">Liên Hệ</a></li>
                </ul>

                <!-- BỘ LỌC DUY NHẤT -->
                <form class="navbar-form navbar-left" action="" method="GET" style="margin-left: 18px;">
                    <select name="filter" class="form-control"
                        onchange="window.location.href=this.value;"
                        style="font-weight:600; min-width:160px;">

                        <option value="">-- Chọn loại sản phẩm --</option>

                        <option value="/fashionboy.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "fashionboy.php" ? "selected" : "") ?>>
                            Thời Trang Nam
                        </option>

                        <option value="/fashiongirl.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "fashiongirl.php" ? "selected" : "") ?>>
                            Thời Trang Nữ
                        </option>

                        <option value="/newproduct.php"
                            <?= (basename($_SERVER['PHP_SELF']) == "newproduct.php" ? "selected" : "") ?>>
                            Hàng Mới Về
                        </option>

                    </select>
                </form>

                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <form class="navbar-form" action="/search.php" method="GET">
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
                        <a href="/cart.php">
                            <button type="button" class="btn header-cart">
                                <span class="fa fa-shopping-cart"></span>
                                <span id="cart-total">
                                    <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                                </span> sản phẩm
                            </button>
                        </a>
                    </li>

                    <?php if (!isset($_SESSION['user'])): ?>
                        <li><a href="/user/login.php" style="font-weight:600;">Đăng nhập</a></li>
                        <li><a href="/user/register.php" style="font-weight:600;">Đăng ký</a></li>
                    <?php else: ?>
                        <li><a href="/my_orders.php" style="font-weight:600;"><i class="fa fa-list"></i> Đơn hàng</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="font-weight:600;">
                                <i class="fa fa-user"></i> Chào, <?php echo htmlspecialchars($_SESSION['user']['fullname']); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a href="/user/profile.php"><i class="fa fa-user-circle-o"></i> Thông tin cá nhân</a></li>
                                <li><a href="/my_orders.php"><i class="fa fa-list"></i> Đơn hàng của tôi</a></li>
                                <li class="divider"></li>
                                <li><a href="/user/logout.php" style="color:red;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                </ul>

            </div>
        </div>
    </nav>
</header>