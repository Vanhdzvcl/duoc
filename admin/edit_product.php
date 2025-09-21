<?php
require __DIR__ . '/../includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: /shoe_store/login.php?next=/shoe_store/admin/");
  exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { echo "Không tìm thấy sản phẩm"; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $description = trim($_POST['description']);
  $image = $p['image'];

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $fname = 'img_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
    $target = __DIR__ . '/../assets/images/' . $fname;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $image = $fname;
  }

  $upd = $conn->prepare("UPDATE products SET name=?, price=?, image=?, description=? WHERE id=?");
  $upd->bind_param("sdssi", $name, $price, $image, $description, $id);
  if ($upd->execute()) {
    header("Location: /shoe_store/admin/");
    exit;
  } else {
    $error = "Không cập nhật được.";
  }
}
?>
<!DOCTYPE html>
<html><head>
  <meta charset="utf-8"><title>Sửa sản phẩm</title>
  <link rel="stylesheet" href="/shoe_store/assets/css/style.css">
</head><body>
<div class="container">
  <h1>Sửa sản phẩm</h1>
  <?php if(isset($error)): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <form class="form" method="post" enctype="multipart/form-data">
    <label>Tên</label><input name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required>
    <label>Giá (VNĐ)</label><input name="price" type="number" min="0" step="1000" value="<?php echo htmlspecialchars($p['price']); ?>" required>
    <label>Mô tả</label><textarea name="description" rows="4"><?php echo htmlspecialchars($p['description']); ?></textarea>
    <label>Ảnh (tùy chọn)</label><input type="file" name="image" accept="image/*">
    <div class="actions">
      <button class="btn primary">Lưu</button>
      <a class="btn" href="/shoe_store/admin/">Hủy</a>
    </div>
  </form>
</div>
</body></html>
