<?php
require __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <h1>Danh sách sản phẩm</h1>
  <?php
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    if ($q !== '') {
      $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') ORDER BY id DESC");
      $stmt->bind_param("s", $q);
    } else {
      $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
    }
    $stmt->execute();
    $res = $stmt->get_result();
  ?>
  <div class="grid">
  <?php while($p = $res->fetch_assoc()): ?>
    <div class="card">
      <link rel="stylesheet" href="assets/css/style.css">

      <img src="assets/images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
      <div class="p16">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p class="price"><?php echo number_format($p['price']); ?> VNĐ</p>
        <div style="display:flex;gap:8px">
          <a class="btn" href="product.php?id=<?php echo $p['id']; ?>">Chi tiết</a>
          <a class="btn primary" href="cart.php?action=add&id=<?php echo $p['id']; ?>">Thêm  giỏ hàng</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['message'])): ?>
    <div class="overlay" id="popupOverlay">
        <div class="popup">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <?php echo $_SESSION['message']; ?>
        </div>
    </div>
    <style>
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .popup {
            position: relative;
            background: #fff;
            padding: 30px 50px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            color: green;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            text-align: center;
            animation: fadeIn 0.5s ease;
            max-width: 400px;
        }
        .close-btn {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
        }
        .close-btn:hover {
            color: red;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.8);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
    <script>
        function closePopup() {
            document.getElementById('popupOverlay').style.display = 'none';
        }
    </script>
<?php 
unset($_SESSION['message']);
endif;
?>
