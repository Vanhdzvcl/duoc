<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php?next=checkout.php");
  exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
  header("Location: cart.php");
  exit;
}

// place order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $total = 0;
  foreach ($cart as $it) $total += $it['product']['price'] * $it['quantity'];

  $conn->begin_transaction();
  try {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $_SESSION['user']['id'], $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
    foreach ($cart as $pid => $it) {
      $pid_i = (int)$pid;
      $qty_i = (int)$it['quantity'];
      $price_d = (float)$it['product']['price'];
      $itemStmt->bind_param("iiid", $order_id, $pid_i, $qty_i, $price_d);
      $itemStmt->execute();
    }
    $conn->commit();
    $_SESSION['cart'] = [];
    $success = $order_id;
  } catch (Exception $e) {
    $conn->rollback();
    $error = $e->getMessage();
  }
}
?>
<div class="container">
  <h1>Thanh toán</h1>
  <?php if(isset($success)): ?>
    <div class="alert">Đặt hàng thành công! Mã đơn: #<?php echo $success; ?>.
      <a class="btn" href="index.php">Về trang chủ</a>
    </div>
  <?php else: ?>
    <div class="form">
      <h2>Xác nhận đơn hàng</h2>
      <p>Tên: <strong><?php echo htmlspecialchars($_SESSION['user']['fullname']); ?></strong></p>
      <p>Email: <strong><?php echo htmlspecialchars($_SESSION['user']['email']); ?></strong></p>
      <form method="post">
        <button class="btn primary">Xác nhận đặt hàng</button>
        <a class="btn" href="cart.php">Quay lại giỏ hàng</a>
      </form>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
