<?php
session_start();
include('conn.php');

// ✅ Check if user is logged in before updating `is_online`
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("UPDATE admin_tb SET is_online = 0 WHERE tbl_user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
}

// ✅ Destroy session AFTER updating user status
session_unset();
session_destroy();

// ✅ Redirect to login page
header("Location: index.php");
exit();
?>
