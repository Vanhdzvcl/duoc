<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ShoeStore</title>
  <link rel="stylesheet" href="<?php echo htmlspecialchars($base); ?>/assets/css/style.css">
  <!-- Bạn có thể thêm icon, fonts... -->
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php


// Tạo biến giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Lấy số lượng sản phẩm trong giỏ hàng
$total_items = count($_SESSION['cart']);

// Lấy danh sách ID sản phẩm từ giỏ hàng (không dùng array_column)
$product_ids = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['id'])) {
            $product_ids[] = $item['id'];
        }
    }
}
?>
<header style="display:flex;justify-content:space-between;align-items:center;padding:15px;background:#f8f9fa;">
    <div style="font-size:24px;font-weight:bold;">
        <a href="index.php" style="text-decoration:none;color:black;">ShoeStore</a>
        <link rel="stylesheet" href="assets/css/style.css">

    </div>
    <div>
        <form action="search.php" method="GET" style="display:inline-flex;">
            <input type="text" name="q" placeholder="Tìm giày..." style="padding:8px;">
            <button type="submit" style="padding:8px;">Tìm</button>
        </form>
        <a href="cart.php" style="margin-left:20px;text-decoration:none;">
            🛒 Giỏ hàng (<?php echo $total_items; ?>)
        </a>
    </div>
</header>
