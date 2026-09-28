<?php
session_start();
require_once 'conn.php'; // Your database connection

// Check if data is sent properly
if (isset($_POST['quiz_id'], $_POST['score'], $_POST['coins'], $_POST['total_questions'], $_POST['correct_answers'])) {

    $user_id = $_SESSION['user_id'];
    $quiz_id = intval($_POST['quiz_id']);
    $score = intval($_POST['score']);
    $coins = floatval($_POST['coins']);
    $correct_answers = intval($_POST['correct_answers']);

    try {
        // ✅ Check if the user already completed this quiz
        $stmt = $conn->prepare("SELECT * FROM quiz_attempts WHERE user_id = :user_id AND quiz_id = :quiz_id");
        $stmt->execute([
            ':user_id' => $user_id,
            ':quiz_id' => $quiz_id
        ]);
        $existingAttempt = $stmt->fetch();

        if ($existingAttempt) {
            echo "❌ You have already completed this quiz. Your previous score: {$existingAttempt['score']} and coins earned: {$existingAttempt['coins']}.";
        } else {
            // ✅ Save the attempt to the quiz_attempts table
            $stmt = $conn->prepare("INSERT INTO quiz_attempts (user_id, quiz_id, score, coins, correct_answers) 
                                    VALUES (:user_id, :quiz_id, :score, :coins, :correct_answers)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':quiz_id' => $quiz_id,
                ':score' => $score,
                ':coins' => $coins,
                ':correct_answers' => $correct_answers
            ]);

            // ✅ Save the progress to the quiz_progress table
            $stmt = $conn->prepare("INSERT INTO quiz_progress (user_id, quiz_id, score) 
                                    VALUES (:user_id, :quiz_id, :score)
                                    ON DUPLICATE KEY UPDATE score = :score");
            $stmt->execute([
                ':user_id' => $user_id,
                ':quiz_id' => $quiz_id,
                ':score' => $score
            ]);

            // ✅ Update the `admin_tb` table with the new total score and coins
            $stmt = $conn->prepare("UPDATE admin_tb 
                                    SET score = score + :score, coins = coins + :coins 
                                    WHERE tbl_user_id = :user_id");
            $stmt->execute([
                ':score' => $score,
                ':coins' => $coins,
                ':user_id' => $user_id
            ]);

            echo "✅ Quiz attempt saved successfully!";
        }
    } catch (PDOException $e) {
        die("❌ Error: " . $e->getMessage());
    }

} else {
    echo "❌ Missing required data. Please try again.";
}
