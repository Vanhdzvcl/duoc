<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add / remove / update actions
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action === 'add' && $id) {
  // fetch product
  $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  if ($p = $stmt->get_result()->fetch_assoc()) {
    if (!isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id] = ['product'=>$p, 'quantity'=>1];
    } else {
      $_SESSION['cart'][$id]['quantity'] += 1;
    }
  }
  header("Location: cart.php");
  exit;
}

if ($action === 'remove' && $id) {
  unset($_SESSION['cart'][$id]);
  header("Location: cart.php");
  exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD']==='POST') {
  foreach ($_POST['qty'] as $pid => $qty) {
    $pid = intval($pid);
    $qty = max(1, intval($qty));
    if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid]['quantity'] = $qty;
  }
  header("Location: cart.php");
  exit;
}

// compute total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
  $total += $item['product']['price'] * $item['quantity'];
}
?>
<div class="container">
  <h1>Giỏ hàng</h1>
  <?php if(empty($_SESSION['cart'])): ?>
    <div class="empty">Giỏ hàng trống. <a class="btn" href="index.php">Tiếp tục mua sắm</a></div>
  <?php else: ?>
    <form method="post" action="cart.php?action=update">
      <table class="table">
        <thead><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tạm tính</th><th></th></tr></thead>
        <tbody>
          <?php foreach($_SESSION['cart'] as $pid => $item): ?>
          <tr>
            <td style="display:flex;align-items:center;gap:12px">
              <img src="assets/images/<?php echo htmlspecialchars($item['product']['image']); ?>" style="width:64px;height:48px;object-fit:cover;border:1px solid #eee;border-radius:8px">
              <?php echo htmlspecialchars($item['product']['name']); ?>
            </td>
            <td><?php echo number_format($item['product']['price']); ?> VNĐ</td>
            <td><input type="number" min="1" name="qty[<?php echo $pid; ?>]" value="<?php echo $item['quantity']; ?>" style="width:72px"></td>
            <td><?php echo number_format($item['product']['price']*$item['quantity']); ?> VNĐ</td>
            <td><a class="btn" href="cart.php?action=remove&id=<?php echo $pid; ?>">Xóa</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
        <a class="btn" href="index.php">← Tiếp tục mua</a>
        <div style="display:flex;align-items:center;gap:12px">
          <strong>Tổng: <?php echo number_format($total); ?> VNĐ</strong>
          <a class="btn primary" href="checkout.php">Thanh toán</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
