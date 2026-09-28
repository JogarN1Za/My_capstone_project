<?php
include("conn.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$earned = 0.10;

try {
    $stmt = $conn->prepare("UPDATE admin_tb SET coins = coins + :amount WHERE tbl_user_id = :id");
    $stmt->execute([
        ':amount' => $earned,
        ':id' => $user_id
    ]);
    echo json_encode(['status' => 'success', 'earned' => number_format($earned, 2)]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
