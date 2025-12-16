<?php
$price_filter_sql = "";
$price_filter_title = "";

if (isset($_GET['price_range'])) {
    $range = intval($_GET['price_range']);
    switch ($range) {
        case 1: // Dưới 100k
            $price_filter_sql = "price < 100000";
            $price_filter_title = "Sản phẩm dưới 100.000đ";
            break;
        case 2: // 100k - 300k
            $price_filter_sql = "price BETWEEN 100000 AND 300000";
            $price_filter_title = "Sản phẩm từ 100.000đ - 300.000đ";
            break;
        case 3: // 300k - 500k
            $price_filter_sql = "price BETWEEN 300000 AND 500000";
            $price_filter_title = "Sản phẩm từ 300.000đ - 500.000đ";
            break;
        case 4: // Trên 500k
            $price_filter_sql = "price > 500000";
            $price_filter_title = "Sản phẩm trên 500.000đ";
            break;
    }
}
