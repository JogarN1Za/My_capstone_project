<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {

    $user_id = $_SESSION['user_id'];
    $comment = htmlspecialchars($_POST['comment']);
    $imagePaths = [];
    $uploadDir = 'uploads/';

    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if files are uploaded
    if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {  // Check if the file was actually uploaded
                $fileName = basename($_FILES['image']['name'][$key]);
                $targetPath = $uploadDir . time() . '_' . $fileName;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $imagePaths[] = $targetPath;
                } else {
                    // Show error if the file couldn't be moved
                    die("Failed to upload image: " . $_FILES['image']['name'][$key]);
                }
            }
        }
    }

    // Store image paths as a JSON string
    $imagePathsJSON = json_encode($imagePaths);

    try {
        // Insert report into the database
        $stmt = $conn->prepare("INSERT INTO reports (user_id, comment, image_paths) VALUES (:user_id, :comment, :image_paths)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':comment' => $comment,
            ':image_paths' => $imagePathsJSON
        ]);

        // Redirect back with success message
        header("Location: report.php?success=1");
        exit();
        
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    die("Invalid Request");
}
