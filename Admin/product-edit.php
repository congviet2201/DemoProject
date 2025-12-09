<?php
require_once('../model/header.php');
require_once("../model/connect.php");
error_reporting(2);

// Thông báo kết quả
if (isset($_GET['idProduct'])) {
    if (isset($_GET['es'])) {
        echo "<script>alert('Bạn đã sửa sản phẩm thành công!');</script>";
    }
    if (isset($_GET['ef'])) {
        echo "<script>alert('Sửa sản phẩm thất bại!');</script>";
    }
}

// Kiểm tra idProduct
if (isset($_GET['idProduct'])) {

    $idProduct = intval($_GET['idProduct']);

    // Dùng prepared statement
    $sql = "SELECT * FROM products WHERE id = :id";
    $stm = $conn->prepare($sql);
    $stm->execute(['id' => $idProduct]);
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "<h3>Không tìm thấy sản phẩm!</h3>";
        exit;
    }

    $thumImage = "../" . $row['image'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../dist/css/sb-admin-2.css">
    <link rel="stylesheet" href="../dist/css/timeline.css">

    <body>
    </body>

    </html>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Chỉnh sửa sản phẩm</h1>
                </div>

                <div class="col-lg-7" style="padding-bottom:120px">

                    <!-- FORM EDIT -->
                    <form action="productedit-back.php?idProduct=<?php echo $row['id']; ?>"
                        method="POST" enctype="multipart/form-data">

                        <!-- Tên sản phẩm -->
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" class="form-control"
                                name="txtName" value="<?php echo $row['name']; ?>" required>
                        </div>

                        <!-- Danh mục sản phẩm -->
                        <div class="form-group">
                            <label>Danh mục sản phẩm</label>
                            <select class="form-control" name="category">

                                <!-- Danh mục đang chọn -->
                                <?php
                                $getCate = $conn->prepare("SELECT * FROM categories WHERE id = :id");
                                $getCate->execute(['id' => $row['category_id']]);
                                $cateNow = $getCate->fetch(PDO::FETCH_ASSOC);

                                if ($cateNow) {
                                    echo "<option value='{$cateNow['id']}' selected>{$cateNow['name']}</option>";
                                }

                                // Load các danh mục khác
                                $sqlCate = "SELECT * FROM categories WHERE id <> :id";
                                $stmCate = $conn->prepare($sqlCate);
                                $stmCate->execute(['id' => $row['category_id']]);

                                while ($c = $stmCate->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Hình ảnh -->
                        <div class="form-group">
                            <label>Chọn hình ảnh sản phẩm</label>
                            <input type="file" name="FileImage">
                            <br>
                            <img src="<?php echo $thumImage; ?>" width="150" height="150">
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group">
                            <label>Mô tả sản phẩm</label>
                            <textarea class="form-control" rows="3"
                                name="txtDescript"><?php echo $row['description']; ?></textarea>
                        </div>

                        <!-- Giá -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giá sản phẩm</label>
                                    <input type="number" class="form-control" name="txtPrice"
                                        value="<?php echo $row['price']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phần trăm giảm</label>
                                    <input type="number" class="form-control" name="txtSalePrice"
                                        value="<?php echo $row['saleprice']; ?>" min="0" max="50">
                                </div>
                            </div>
                        </div>

                        <!-- Số lượng -->
                        <div class="form-group">
                            <label>Số lượng sản phẩm</label>
                            <input type="number" class="form-control" name="txtNumber"
                                value="<?php echo $row['quantity']; ?>">
                        </div>

                        <!-- Keyword -->
                        <div class="form-group">
                            <label>Từ khóa tìm kiếm</label>
                            <input class="form-control" name="txtKeyword"
                                value="<?php echo $row['keyword']; ?>">
                        </div>

                        <!-- Tình trạng -->
                        <div class="form-group">
                            <label>Tình trạng sản phẩm</label><br>

                            <label class="radio-inline">
                                <input type="radio" name="status" value="0"
                                    <?php echo ($row['status'] == 0 ? "checked" : ""); ?>>
                                Còn hàng
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="status" value="1"
                                    <?php echo ($row['status'] == 1 ? "checked" : ""); ?>>
                                Hết hàng
                            </label>
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" name="editProduct"
                            class="btn btn-warning btn-lg">
                            Chỉnh sửa sản phẩm
                        </button>

                    </form>
                    <!-- END FORM -->

                </div>
            </div>

        </div>
    </div>

<?php } ?>