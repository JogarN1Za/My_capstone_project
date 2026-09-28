<?php
include('conn.php');

// ✅ Ensure JSON output
header('Content-Type: application/json');

// ✅ Validate quiz_id
$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id || !is_numeric($quiz_id)) {
    echo json_encode(['error' => 'Invalid quiz ID.']);
    exit;
}

// ✅ Fetch questions and the correct answer from the database
$stmt = $conn->prepare("
    SELECT id, question, option_a, option_b, option_c, option_d, correct_answer
    FROM quiz_questions
    WHERE quiz_id = :quiz_id
");
$stmt->execute([':quiz_id' => $quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    echo json_encode(['error' => 'No questions available for this quiz.']);
    exit;
}

// ✅ Format the data properly, including the correct answer
$formattedQuestions = [];
foreach ($questions as $question) {
    $formattedQuestions[] = [
        'id' => $question['id'],
        'question_text' => $question['question'],
        'options' => [
            'A' => $question['option_a'],
            'B' => $question['option_b'],
            'C' => $question['option_c'],
            'D' => $question['option_d'],
        ],
        'correct_option' => $question['correct_answer'], // ✅ Add the correct answer here
    ];
}

// ✅ Return JSON response
echo json_encode(['questions' => $formattedQuestions]);
?>