<form method="GET" style="margin-bottom: 20px; max-width: 250px;">
    <select name="price_filter" class="form-control" onchange="this.form.submit()">
        <option value="">-- Lọc theo giá --</option>
        <option value="1" <?= isset($_GET['price_filter']) && $_GET['price_filter'] == 1 ? 'selected' : '' ?>>Dưới 100.000đ</option>
        <option value="2" <?= isset($_GET['price_filter']) && $_GET['price_filter'] == 2 ? 'selected' : '' ?>>100.000đ - 200.000đ</option>
        <option value="3" <?= isset($_GET['price_filter']) && $_GET['price_filter'] == 3 ? 'selected' : '' ?>>200.000đ - 500.000đ</option>
        <option value="4" <?= isset($_GET['price_filter']) && $_GET['price_filter'] == 4 ? 'selected' : '' ?>>Trên 500.000đ</option>
    </select>
</form>