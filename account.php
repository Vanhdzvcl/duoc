<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
$u = $_SESSION['user'];
?>
<div class="container">
  <h1>Tài khoản</h1>
  <p>Họ tên: <strong><?php echo htmlspecialchars($u['fullname']); ?></strong></p>
  <p>Email: <strong><?php echo htmlspecialchars($u['email']); ?></strong></p>
  <p>Quyền: <strong><?php echo htmlspecialchars($u['role']); ?></strong></p>
  <a class="btn" href="logout.php">Đăng xuất</a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
