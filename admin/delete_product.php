<?php
require __DIR__ . '/../includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: /shoe_store/login.php?next=/shoe_store/admin/");
  exit;
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: /shoe_store/admin/");
exit;
