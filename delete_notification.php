<?php
include('conn.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $notificationId = $data['id'];
    $user_id = $_SESSION['user_id'];

    if ($notificationId && $user_id) {
        $stmt = $conn->prepare("UPDATE notifications SET deleted = 1 WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $notificationId, ':user_id' => $user_id]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
