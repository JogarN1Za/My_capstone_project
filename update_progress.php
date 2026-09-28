<?php
session_start();
include('conn.php');

$user_id = $_SESSION['user_id'];
$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$part_id = filter_input(INPUT_GET, 'part', FILTER_VALIDATE_INT);

if (!$user_id || !$category_id || !$part_id) {
    header("Location: error.php?msg=Invalid submission data");
    exit();
}

// Handle "Mark All as Viewed"
if (isset($_POST['mark_all_viewed']) && $_POST['mark_all_viewed'] == 1) {
    // Fetch the total number of tutorials for this part
    $stmt_total = $conn->prepare("SELECT COUNT(*) FROM tutorial WHERE part_id = :part_id");
    $stmt_total->execute([':part_id' => $part_id]);
    $total_tutorials = (int) $stmt_total->fetchColumn();

    // Update progress to mark all tutorials as completed for this part
    $stmt_update_all = $conn->prepare("
        INSERT INTO progress (user_id, part_id, completed_tutorials, total_tutorials, progress_percentage)
        VALUES (:user_id, :part_id, :total_tutorials, :total_tutorials, 100)
        ON DUPLICATE KEY UPDATE
            completed_tutorials = :total_tutorials,
            total_tutorials = :total_tutorials,
            progress_percentage = 100
    ");
    $stmt_update_all->execute([
        ':user_id' => $user_id,
        ':part_id' => $part_id,
        ':total_tutorials' => $total_tutorials
    ]);

    header("Location: tutorial.php?category=$category_id&part=$part_id&all_viewed=true");
    exit();
}

// Handle "Mark as Completed" for the entire part
if (isset($_POST['complete_tutorial'])) {
    // Count total tutorials for this part
    $stmt_total = $conn->prepare("SELECT COUNT(*) FROM tutorial WHERE part_id = :part_id");
    $stmt_total->execute([':part_id' => $part_id]);
    $total_tutorials = (int) $stmt_total->fetchColumn();

    // Update progress to mark all tutorials as completed for this part
    $stmt_update_all = $conn->prepare("
        INSERT INTO progress (user_id, part_id, completed_tutorials, total_tutorials, progress_percentage)
        VALUES (:user_id, :part_id, :total_tutorials, :total_tutorials, 100)
        ON DUPLICATE KEY UPDATE
            completed_tutorials = :total_tutorials,
            total_tutorials = :total_tutorials,
            progress_percentage = 100
    ");
    $stmt_update_all->execute([
        ':user_id' => $user_id,
        ':part_id' => $part_id,
        ':total_tutorials' => $total_tutorials
    ]);

    // Optionally, update progress_details to mark all as viewed
    $stmt_mark_all_viewed = $conn->prepare("
        INSERT INTO progress_details (user_id, part_id, tutorial_id, viewed_at)
        SELECT :user_id, :part_id, id, NOW()
        FROM tutorial
        WHERE part_id = :part_id
        ON DUPLICATE KEY UPDATE viewed_at = NOW()
    ");
    $stmt_mark_all_viewed->execute([
        ':user_id' => $user_id,
        ':part_id' => $part_id
    ]);

    header("Location: tut_part.php?category=$category_id&part=$part_id&completed=true");
    exit();
}

header("Location: tutorial.php?category=$category_id&part=$part_id");
exit();
?>
