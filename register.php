<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = trim($_POST['fullname']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if ($fullname === '' || $email === '' || $password === '') {
    $error = "Vui lòng nhập đầy đủ thông tin.";
  } else {
    // Kiểm tra email trùng
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
      $error = "Email đã tồn tại.";
    } else {
      // Dùng md5 (ít bảo mật nhưng chạy được trên PHP 5.4)
      $hash = md5($password);

      // Thêm user mới
      $sql = "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'user')";
      $ins = $conn->prepare($sql);

      if (!$ins) {
        die("Lỗi prepare: " . $conn->error);
      }

      $ins->bind_param("sss", $fullname, $email, $hash);

      if ($ins->execute()) {
        header("Location: login.php?registered=1");
        exit;
      } else {
        $error = "Có lỗi khi tạo tài khoản: " . $ins->error;
      }
    }
  }
}
?>
<div class="container">
  <div class="form">
    <h2>Đăng ký</h2>
    <?php if(isset($error)): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post">
      <label>Họ tên</label>
      <input type="text" name="fullname" required>
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Mật khẩu</label>
      <input type="password" name="password" required>
      <div class="actions">
        <button class="btn primary" type="submit">Tạo tài khoản</button>
        <a class="btn" href="login.php">Đăng nhập</a>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
