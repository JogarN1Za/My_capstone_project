<?php
include('conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("❌ You must be logged in to report quizzes.");
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_POST['quiz_id'] ?? null;
$comment = $_POST['comment'] ?? null;

if (!$quiz_id || !$comment) {
    die("❌ Missing data. Make sure to enter your comment.");
}

$stmt = $conn->prepare("
    INSERT INTO reports (user_id, quiz_id, comment, status)
    VALUES (:user_id, :quiz_id, :comment, 'Pending')
");
$stmt->execute([
    ':user_id' => $user_id,
    ':quiz_id' => $quiz_id,
    ':comment' => $comment
]);

echo "✅ Report submitted successfully.";
