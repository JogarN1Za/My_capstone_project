<?php
session_start();
include('conn.php'); // Ensure this is correct

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'User not authenticated.';
        echo json_encode($response);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $exercise_id = filter_input(INPUT_POST, 'exercise_id', FILTER_VALIDATE_INT);
    $user_code = filter_input(INPUT_POST, 'user_code', FILTER_SANITIZE_STRING);

    if ($exercise_id === false || $exercise_id <= 0 || empty($user_code)) {
        $response['message'] = 'Invalid input.';
        echo json_encode($response);
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO user_submissions (user_id, exercise_id, submitted_code) VALUES (:user_id, :exercise_id, :submitted_code)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':exercise_id' => $exercise_id,
            ':submitted_code' => $user_code
        ]);

        $response = ['status' => 'success', 'message' => 'Code submitted successfully!'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $response['message'] = 'Database error occurred.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
