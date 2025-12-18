<?php
require_once __DIR__ . '/connect.php';
// Success
if (isset($_GET['cs'])) {
    echo "<script type=\"text/javascript\">alert(\"Gửi liên hệ thành công!\");</script>";
} else {
    echo "";
}
// Fail
if (isset($_GET['cf'])) {
    echo "<script type=\"text/javascript\">alert(\"Gửi liên hệ thất bại!\");</script>";
} else {
    echo "";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>VIE Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale =1">
    <meta charset="utf-8">
    <meta name="title" content="VIE Shop - fashion vie" />
    <meta name="description" content="VIE Shop - fashion vie" />
    <meta name="keywords" content="VIE Shop - fashion vie" />
    <meta name="author" content="Hôih My" />
    <meta name="author" content="Y Blir" />
    <link rel="icon" type="image/png" href="/images/vie_logo.png">
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../js/mylishop.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <script src='../js/wow.js'></script>
    <!-- Bootstrap Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
    <!-- button top -->
    <a href="../index.php" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- header -->
    <?php include '../model/header.php'; ?>
    <!-- /header -->

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="../index.php"> Trang chủ </a></li>
            <li> Liên hệ </li>
        </ul><!-- /breadcrumb -->
        <div class="row">
            <div class="col-md-12 col-sm-16 col-xs-12">
                <div class="titles">
                    <center>
                        <h3><strong> THÔNG TIN LIÊN HỆ </strong></h3>
                    </center>
                </div>
                <form name="form-lien-he" action="lienhe_back.php" method="POST" accept-charset="utf-8">
                    <div class="contents">
                        <div class="form-group">
                            <label>Họ và tên: <span style="color:#f00">&#42;</span></label>
                            <input name="contact-name" placeholder="Nhập họ tên đầy đủ" class="form-control" required="required" maxlength="255" type="text" id="contact-name">
                        </div>

                        <div class="form-group">
                            <label>Email: <span style="color:#f00">&#42;</span></label>
                            <input name="contact-email" placeholder="Nhập email của bạn" class="form-control" required="required" maxlength="255" type="email" id="contact-email">
                        </div>

                        <div class="form-group">
                            <label>Tiêu đề: <span style="color:#f00">&#42;</span></label>
                            <input name="contact-subject" placeholder="Nhập tiêu đề của bạn" class="form-control" required="required" maxlength="255" type="text" id="contact-subject">
                        </div>

                        <div class="form-group">
                            <label>Nội dung: <span style="color:#f00">&#42;</span></label>
                            <textarea name="contact-content" placeholder="Nhập thông tin cần liên hệ..." class="form-control" id="ContactContent" rows="5" required></textarea>
                        </div>
                        <center><button type="submit" name="sendcontact" class="btn btn-info"> Gửi liên hệ </button></center>
                    </div><!-- /content -->
                </form>
            </div><!-- /col -->
        </div><!-- /row -->
    </div><!-- /container -->

    <!-- Maps -->
    <div class="container-fluid">
        <div class="row">
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d958.5247181884388!2d108.24206672970746!3d16.060358250494478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421836ed15dfc9%3A0x99c3cc369a33576c!2sPasserelles+num%C3%A9riques+Vietnam!5e0!3m2!1sen!2s!4v1513938605489" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen width="100%" height="400" frameborder="0" padding="0px;"></iframe>
            </div><!-- /map -->
        </div><!-- /row -->
    </div><!-- /container-fluid -->
    <script>
        new WOW().init();
    </script>
</body>

</html>