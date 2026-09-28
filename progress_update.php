<?php
include('conn.php'); // Include database connection
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$part_id = $_POST['part_id'] ?? null;
$progress = $_POST['progress'] ?? null;

// ✅ Validate input
if (!$part_id || !$progress || !is_numeric($progress) || $progress < 0 || $progress > 100) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
    exit();
}

try {
    // ✅ Update or Insert Progress
    $stmt = $conn->prepare("
        INSERT INTO progress (user_id, part_id, progress_percentage) 
        VALUES (:user_id, :part_id, :progress)
        ON DUPLICATE KEY UPDATE progress_percentage = :progress
    ");
    $stmt->execute([':user_id' => $user_id, ':part_id' => $part_id, ':progress' => $progress]);

    echo json_encode(['status' => 'success', 'message' => 'Progress updated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>