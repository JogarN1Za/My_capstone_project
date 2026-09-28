<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];
$part_id = $_POST['part_id'] ?? null;
$price = $_POST['price'] ?? 0;

if (!$part_id || !is_numeric($price)) {
    echo "Invalid data";
    exit;
}

// Check coin balance
$stmt = $conn->prepare("SELECT coins FROM admin_tb WHERE tbl_user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$currentCoins = $user['coins'] ?? 0;

if ($currentCoins < $price) {
    echo "Insufficient coins";
    exit;
}

try {
    // Deduct coins
    $conn->beginTransaction();

    $stmt = $conn->prepare("UPDATE admin_tb SET coins = coins - :price WHERE tbl_user_id = :user_id");
    $stmt->execute(['price' => $price, 'user_id' => $user_id]);

    // Insert purchase
    $stmt = $conn->prepare("INSERT INTO tbl_purchase (user_id, part_id) VALUES (:user_id, :part_id)");
    $stmt->execute(['user_id' => $user_id, 'part_id' => $part_id]);

    $conn->commit();
    echo "success";
} catch (Exception $e) {
    $conn->rollBack();
    echo "Database error: " . $e->getMessage();
}
?>
