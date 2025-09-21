<?php
require __DIR__ . '/../includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: /shoe_store/login.php?next=/shoe_store/admin/");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Sản phẩm</title>
  <link rel="stylesheet" href="/shoe_store/assets/css/style.css">
</head>
<body>
<div class="container">
  <h1>Quản lý sản phẩm</h1>
  <a class="btn primary" href="add_product.php">+ Thêm sản phẩm</a>
  <table class="table" style="margin-top:12px">
    <thead><tr><th>ID</th><th>Ảnh</th><th>Tên</th><th>Giá</th><th></th></tr></thead>
    <tbody>
      <?php
        $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
        while($p = $res->fetch_assoc()):
      ?>
      <tr>
        <td><?php echo $p['id']; ?></td>
        <td><img src="/shoe_store/assets/images/<?php echo htmlspecialchars($p['image']); ?>" style="width:64px;height:48px;object-fit:cover;border:1px solid #eee;border-radius:8px"></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo number_format($p['price']); ?> VNĐ</td>
        <td>
          <a class="btn" href="edit_product.php?id=<?php echo $p['id']; ?>">Sửa</a>
          <a class="btn" href="delete_product.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
