<?php
include('conn.php'); // Include your database connection
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "❌ Error: User not logged in.";
    exit();
}

// ✅ Get category ID dynamically from the URL or default to 1
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 1; // Default to 1 if not provided
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

try {
    // ✅ Fetch progress data for the specified category and user
    $stmt = $conn->prepare("
        SELECT * 
        FROM progress 
        WHERE part_id IN (SELECT id FROM tutorial WHERE tu_cat_id = :category_id)
        AND user_id = :user_id
        LIMIT 0, 25
    ");
    $stmt->execute([':category_id' => $category_id, ':user_id' => $user_id]);
    $progress_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Debugging: Print the progress data
    if (empty($progress_data)) {
        echo "No progress data found for the specified category (Category ID: $category_id).";
    } else {
        echo "<h3>Progress Data for Category ID: $category_id</h3>";
        echo "<pre>";
        print_r($progress_data);
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>