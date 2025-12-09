<meta charset="utf-8">
<?php
// Hiển thị lỗi trong dev — bạn có thể tắt ở production
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../model/connect.php';

// Chỉ xử lý khi form được submit
if (isset($_POST['editProduct'])) {

    // Khởi tạo biến mặc định
    $keywordPr = '';
    $descriptPr = '';
    $status = 0;
    $image = ''; // tên file ảnh sẽ lưu vào DB
    $uploadOk = 1;

    // Lấy idProduct an toàn
    if (isset($_GET['idProduct'])) {
        $idProduct = intval($_GET['idProduct']);
    } else {
        // Nếu không có idProduct thì dừng
        echo 'Không có id sản phẩm';
        exit;
    }

    // --- Xử lý ảnh (nếu có) ---
    // Kiểm tra xem file upload tồn tại
    if (isset($_FILES['FileImage']) && $_FILES['FileImage']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Đặt đường dẫn đích
        $uploadDir = '../uploads/'; // khuyến nghị lưu trong thư mục uploads
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $image = basename($_FILES["FileImage"]["name"]);
        $target_file = $uploadDir . $image;

        // Kiểm tra đúng là ảnh
        $check = getimagesize($_FILES["FileImage"]["tmp_name"]);
        if ($check === false) {
            // Không phải ảnh -> quay về trang edit với thông báo
            header("Location: product-edit.php?idProduct={$idProduct}&notimage=1");
            exit();
        }

        // Kiểm tra nếu file đã tồn tại -> đổi tên để tránh ghi đè
        if (file_exists($target_file)) {
            // thêm timestamp để tránh trùng tên
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            $nameOnly = pathinfo($image, PATHINFO_FILENAME);
            $image = $nameOnly . '_' . time() . '.' . $ext;
            $target_file = $uploadDir . $image;
        }

        // Di chuyển file lên server
        if (!move_uploaded_file($_FILES["FileImage"]["tmp_name"], $target_file)) {
            // upload lỗi -> giữ tên ảnh cũ từ form (nếu có) hoặc báo lỗi
            // nếu muốn, bạn có thể redirect về edit với thông báo
            header("Location: product-edit.php?idProduct={$idProduct}&uploadfail=1");
            exit();
        }
    } else {
        // Không upload ảnh mới -> dùng ảnh cũ (nếu input hidden "image" được gửi)
        if (isset($_POST['image']) && $_POST['image'] !== '') {
            $image = $_POST['image'];
        } else {
            // không có ảnh nào -> để rỗng hoặc xử lý theo logic của bạn
            $image = '';
        }
    }

    // --- Lấy các trường khác từ POST (với kiểm tra tồn tại) ---
    $namePr = isset($_POST['txtName']) ? trim($_POST['txtName']) : '';
    $categoryPr = isset($_POST['category']) ? intval($_POST['category']) : null;
    $pricePr = isset($_POST['txtPrice']) ? floatval($_POST['txtPrice']) : 0;
    $salePricePr = isset($_POST['txtSalePrice']) && $_POST['txtSalePrice'] !== '' ? floatval($_POST['txtSalePrice']) : 0;
    $quantityPr = isset($_POST['txtNumber']) ? intval($_POST['txtNumber']) : 0;
    $keywordPr = isset($_POST['txtKeyword']) ? trim($_POST['txtKeyword']) : '';
    $descriptPr = isset($_POST['txtDescript']) ? trim($_POST['txtDescript']) : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    // --- Cập nhật vào DB (prepared statement) ---
    $sql = "UPDATE products SET
                name = :namePr,
                category_id = :categoryPr,
                image = :image,
                description = :descriptPr,
                price = :pricePr,
                saleprice = :salePricePr,
                quantity = :quantityPr,
                keyword = :keywordPr,
                status = :status
            WHERE id = :idProduct";

    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':namePr' => $namePr,
        ':categoryPr' => $categoryPr,
        ':image' => $image,
        ':descriptPr' => $descriptPr,
        ':pricePr' => $pricePr,
        ':salePricePr' => $salePricePr,
        ':quantityPr' => $quantityPr,
        ':keywordPr' => $keywordPr,
        ':status' => $status,
        ':idProduct' => $idProduct
    ]);

    if ($result) {
        // Redirect an toàn (không xuất echo trước header)
        header("Location: product-edit.php?idProduct={$idProduct}&es=editsuccess");
        exit();
    } else {
        header("Location: product-edit.php?idProduct={$idProduct}&ef=editfail");
        exit();
    }
} else {
    echo 'Bạn chưa submit form.';
}
?>