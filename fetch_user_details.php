<?php
// fetch_user_details.php

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection file
include('conn.php');

// Start the session (if you need session data)
session_start();

// Check if the user_id is set in the GET request and is numeric
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    // If not valid, return null (or an error message as JSON)
    echo json_encode(null);
    exit();
}

// Sanitize the user ID
$user_id = intval($_GET['user_id']);

try {
    // Prepare the SQL statement to fetch user details
    $stmt = $conn->prepare("
        SELECT
            atb.tbl_user_id,
            atb.username,
            atb.first_name,
            atb.last_name,
            atb.contact_number,
            atb.email,
            atb.profile_picture,
            atb.score,
            atb.coins,
            y.year_name,
            s.sec_name
        FROM admin_tb atb
        LEFT JOIN year_tb y ON atb.year_id = y.year_id
        LEFT JOIN section_tb s ON atb.sec_id = s.sec_id
        WHERE atb.tbl_user_id = :user_id
    ");

    // Bind the user ID parameter
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the user details as an associative array
    $user_details = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    // Encode the user details (or null if not found) as JSON and echo it
    echo json_encode($user_details);

} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>