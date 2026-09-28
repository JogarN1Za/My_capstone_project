<?php
include('./conn.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$level = $data['level'] ?? 0;
$accuracy = $data['accuracy'] ?? 0;
$timeLeft = $data['time_left'] ?? 0;

// Get level data
$stmt = $conn->prepare("SELECT * FROM typing_levels WHERE level_number = ?");
$stmt->execute([$level]);
$levelData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$levelData) {
    echo json_encode(['message' => 'Level not found']);
    exit;
}

// Set a minimum accuracy to earn reward (optional)
if ($accuracy >= 50) {
    $reward = (float)$levelData['coin_reward'];

    // Update user coins
    $stmt = $conn->prepare("UPDATE users SET coins = coins + ? WHERE id = ?");
    $stmt->execute([$reward, $user_id]);

    echo json_encode(['message' => "Score submitted. ₱{$reward} earned!"]);
} else {
    echo json_encode(['message' => "Accuracy too low. No coins awarded."]);
}
?>
