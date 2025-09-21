<?php
include 'includes/db.php';
include 'includes/header.php';

$search = '';
if (isset($_GET['q'])) {
    $search = mysqli_real_escape_string($conn, $_GET['q']);
}

$query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h2>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($search); ?>"</h2>
    <div class="product-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="product-card">';
                echo '<img src="assets/images/' . $row['image'] . '" alt="' . $row['name'] . '">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p>' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
                echo '<a href="product.php?id=' . $row['id'] . '" class="btn">Xem chi tiết</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Không tìm thấy sản phẩm nào phù hợp.</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
