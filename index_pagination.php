<?php
session_start();
include 'model/connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pagination</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Products</h2>

        <?php
        if (!empty($_SESSION['message'])) {
            echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

        <?php
        // Đếm tổng số dòng
        $stmt = $conn->query("SELECT COUNT(*) AS total FROM products");
        $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Thiết lập phân trang
        $rows_per_page = 10;
        $total_pages   = ceil($total_rows / $rows_per_page);

        $page_current = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($page_current < 1) $page_current = 1;
        if ($page_current > $total_pages) $page_current = $total_pages;

        $start = ($page_current - 1) * $rows_per_page;

        // Lấy dữ liệu sản phẩm
        $sql = "SELECT id, name, category_id, image, description, price, created, quantity 
            FROM products 
            ORDER BY id ASC 
            LIMIT :start, :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":start", $start, PDO::PARAM_INT);
        $stmt->bindValue(":limit", $rows_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category ID</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Created</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($products): ?>
                    <?php foreach ($products as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['category_id'] ?></td>
                            <td><?= $row['image'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['created'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                                <a href="delete.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Không có sản phẩm nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <div class="text-center">
            <?php if ($total_pages > 1): ?>
                <ul class="pagination">

                    <?php if ($page_current > 1): ?>
                        <li><a href="?p=1">First</a></li>
                        <li><a href="?p=<?= $page_current - 1 ?>">Previous</a></li>
                    <?php endif; ?>

                    <li class="active"><a><?= $page_current ?></a></li>

                    <?php if ($page_current < $total_pages): ?>
                        <li><a href="?p=<?= $page_current + 1 ?>">Next</a></li>
                        <li><a href="?p=<?= $total_pages ?>">Last</a></li>
                    <?php endif; ?>

                </ul>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>