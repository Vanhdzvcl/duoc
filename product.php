<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
?>
<div class="container">
<?php if(!$product): ?>
  <div class="alert">Không tìm thấy sản phẩm.</div>
<?php else: ?>
  <div class="grid" style="grid-template-columns: 1fr 1fr;">
    <div>
      <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" style="width:100%;height:auto;object-fit:cover;border-radius:16px;border:1px solid #eee">
    </div>
    <div>
      <h2><?php echo htmlspecialchars($product['name']); ?></h2>
      <p class="price"><?php echo number_format($product['price']); ?> VNĐ</p>
      <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      <div class="actions">
        <a class="btn primary" href="cart.php?action=add&id=<?php echo $product['id']; ?>">Thêm vào giỏ</a>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
