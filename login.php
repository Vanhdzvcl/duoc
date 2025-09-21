<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email === '' || $password === '') {
        $error = "Vui lòng nhập email và mật khẩu.";
    } else {
        // Mã hóa md5 để so sánh
        $password_md5 = md5($password);

        // Kiểm tra user trong DB
        $stmt = $conn->prepare("SELECT id, fullname, role FROM users WHERE email=? AND password=?");
        $stmt->bind_param("ss", $email, $password_md5);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Lưu session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['message'] = "Bạn đã mua sản phẩm thành công";
            header("Location: index.php");
            exit;
        } else {
            $error = "Sai email hoặc mật khẩu.";
        }
    }
}
?>

<div class="container">
  <div class="form">
    <h2>Đăng nhập</h2>
    <?php if(isset($error)): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Mật khẩu</label>
      <input type="password" name="password" required>
      <div class="actions">
        <button class="btn primary" type="submit">Đăng nhập</button>
        <a class="btn" href="register.php">Đăng ký</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
