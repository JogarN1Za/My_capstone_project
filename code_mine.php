<?php
include('conn.php'); // Include your database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get exercise ID and category ID from the URL
$exercise_id = $_GET['exercise_id'] ?? null;
$category_id = $_GET['category_id'] ?? null;

if (!$exercise_id || !is_numeric($exercise_id)) {
    die("<b>❌ Error:</b> Invalid or missing exercise ID.");
}

// Fetch exercise details from the database
try {
    $stmt = $conn->prepare("SELECT id, title, description, category_id, part_id FROM code_ex WHERE id = :exercise_id");
    $stmt->execute([':exercise_id' => $exercise_id]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exercise) {
        die("<b>❌ Error:</b> Code exercise not found.");
    }
} catch (PDOException $e) {
    error_log("Database Error fetching exercise: " . $e->getMessage());
    die("<b>❌ Error:</b> Could not retrieve exercise details. Please try again later.");
}

// Fetch unread notifications count for the top navigation
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
    $stmt->execute([':user_id' => $user_id]);
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);
    $unreadCount = $notification['unread_count'] ?? 0;
} catch (PDOException $e) {
    error_log("Database Error fetching notifications: " . $e->getMessage());
    $unreadCount = 0; // Default to 0 on error
}

// Fetch user coins for the top navigation
try {
    $stmt = $conn->prepare("SELECT coins FROM admin_tb WHERE tbl_user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $userCoins = $userData['coins'] ?? 0.00;
} catch (PDOException $e) {
    error_log("Database Error fetching user coins: " . $e->getMessage());
    $userCoins = 0.00; // Default to 0 on error
}

// --- Sidebar Category Fetching (Consider refactoring to a shared include) ---
try {
    $stmtEasySidebar = $conn->prepare("
        SELECT c.id AS category_id, c.cat_title
        FROM category c
        WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'easy')
        ORDER BY c.id;
    ");
    $stmtEasySidebar->execute();
    $easyCategoriesSidebar = $stmtEasySidebar->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching Easy categories for sidebar: " . $e->getMessage());
    $easyCategoriesSidebar = [];
}

try {
    $stmtMediumSidebar = $conn->prepare("
        SELECT c.id AS category_id, c.cat_title
        FROM category c
        WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'medium')
        ORDER BY c.id;
    ");
    $stmtMediumSidebar->execute();
    $mediumCategoriesSidebar = $stmtMediumSidebar->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching Medium categories for sidebar: " . $e->getMessage());
    $mediumCategoriesSidebar = [];
}

try {
    $stmtHardSidebar = $conn->prepare("
        SELECT c.id AS category_id, c.cat_title
        FROM category c
        WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'hard')
        ORDER BY c.id;
    ");
    $stmtHardSidebar->execute();
    $hardCategoriesSidebar = $stmtHardSidebar->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching Hard categories for sidebar: " . $e->getMessage());
    $hardCategoriesSidebar = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= htmlspecialchars($exercise['title']) ?> - Code Exercise</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
    <style>
        /* Shared Dark Theme Styles (ensure consistency with your site's main CSS) */
        body { background-color: #0a0b0c; color: #e0e0e0; }
        nav.top-nav { background-color:#1f2937; height: 55px; display: flex; align-items: center; padding: 0 20px; position: fixed; top: 20px; left: 0; width: 100%; z-index: 100; border-bottom: 1px solid #334155; justify-content: space-between; }
        .logo { display: flex; align-items: center; }
        .menu-icon { color: #64748b; font-size: 1.3em; cursor: pointer; display: none; }
        .logo-name { font-size: 1.1em; font-weight: bold; color: #f8fafc; margin-left: 10px; }
        .sidebar { position: fixed; top: 75px; left: 0; width: 240px; height: calc(100vh - 75px); background-color: #1e293b; color: #e0e0e0; transform: translateX(-100%); transition: transform 0.3s ease-in-out; z-index: 300; }
        .sidebar.open { transform: translateX(0); }
        .sidebar-content { padding: 15px; overflow-y: auto; height: 100%; }
        .tutorials-heading { font-size: 0.9em; font-weight: bold; margin-bottom: 15px; color: #64748b; text-transform: uppercase; letter-spacing: 0.7px; padding-left: 5px; }
        .lists { list-style: none; padding: 0; margin: 0 0 15px 0; }
        .list-group-heading { font-weight: bold; color: #cbd5e1; padding: 8px 15px; margin-bottom: 3px; font-size: 0.9em; }
        .list-group-heading .nav-link { font-size: 14px; padding: 12px 20px; font-weight: bold; color: #ffffff; display: block; }
        .list-item { margin-bottom: 3px; }
        .nav-link { display: block; color: #a3a3a3; text-decoration: none; padding: 7px 20px; border-radius: 6px; transition: background-color 0.2s ease; font-size: 0.85em; }
        .nav-link:hover { background-color: #334155; color: #f0f0f0; }
        .main { padding: 40px 20px; padding-top: 75px; background-color: #121827; color:#727883; min-height: 100vh; transition: margin-left 0.3s ease-in-out; }
        .main > div { padding: 20px; background-color: #121827; border-radius: 4px; }
        nav.additional-nav { background-color: #374151; height: 20px; display: flex; align-items: center; padding: 0 20px; position: fixed; top: 0; left: 0; width: 100%; z-index: 101; }
        nav.additional-nav .block { display: none; }
        .sidebar-backdrop { position: fixed; top: 75px; left: 0; width: 100%; height: calc(100vh - 75px); background: rgba(0, 0, 0, 0.5); z-index: 250; display: none; }
        .sidebar-backdrop.show { display: block; }
        .right-icons { display: flex; align-items: center; position: relative; }
        .right-links-desktop { display: flex; gap: 10px; }
        .right-icons a { color: #64748b; text-decoration: none; margin-left: 10px; font-size: 0.9em; transition: color 0.2s ease; }
        .right-icons a:hover { color: #f8fafc; }
        .dropdown-toggle-icon { color: #f0f0f0; font-size: 1.5em; cursor: pointer; display: none; margin-left: 10px; }
        .right-dropdown { position: absolute; right: 40px; top: 45px; background-color: #1e293b; border: 1px solid #334155; border-radius: 4px; display: none; flex-direction: column; width: 180px; z-index: 999; }
        .right-icons i { font-size: 24px; }
        .right-dropdown a { color: #cbd5e1; text-decoration: none; padding: 10px; border-bottom: 1px solid #334155; display: block; font-size: 0.9em; }
        .right-dropdown a:hover { background-color: #334155; }
        .right-dropdown.show { display: flex; }

        /* Responsive Adjustments */
        @media (min-width: 769px) {
            .right-links-desktop { display: flex; }
            .dropdown-toggle-icon, .right-dropdown { display: none !important; }
            .sidebar { transform: translateX(0) !important; }
            .main { margin-left: 240px; }
            .menu-icon { display: none !important; }
            .sidebar-backdrop { display: none !important; }
        }
        @media (max-width: 768px) {
            .right-links-desktop { display: none; }
            .dropdown-toggle-icon { display: block; }
            .menu-icon { display: block; margin-right: 10px; }
            .main { margin-left: 0; width: 100%; }
        }

        /* Specific styles for code_mine.php content */
        .btn-gradient {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(to right, #0056b3, #007bff);
        }

        .code-editor-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
        #codeEditor {
            width: 100%;
            height: 400px; /* Adjust height as needed */
            background-color: #1a1a1a;
            color: #f0f0f0;
            border: 1px solid #334155;
            padding: 15px;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 1em;
            resize: vertical;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit-code { /* New class for the Send Code button */
            background: linear-gradient(to right, #007bff, #0056b3); /* Example: Blue gradient */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-submit-code:hover {
            background: linear-gradient(to right, #0056b3, #007bff);
        }
    </style>
</head>
<body>

<nav class="additional-nav"><div class="block"></div></nav>

<nav class="top-nav">
    <div class="logo">
        <i class="bx bx-menu menu-icon"></i>
        <span class="logo-name">Web.Dev.</span>
    </div>

    <div class="right-icons">
        <div class="right-links-desktop">
            <a href="about.php">Cards</a>
            <a href="setting.php">Leader board</a>
            <a href="report.php">Reports</a>
            <a href="type_to_earn.php">Earn Coins</a>
        </div>

        <i class="bx bx-dots-vertical-rounded dropdown-toggle-icon"></i>

        <div class="right-dropdown">
            <a href="about.php">Cards</a>
            <a href="setting.php">Leader board</a>
            <a href="report.php">Reports</a>
            <a href="type_to_earn.php">Earn Coins</a>
        </div>

        <a href="logout.php"><i class='bx bx-log-out'></i></a>
        <a href="achievement.php" class="position-relative">
            <i class='bx bx-bell'></i>
            <?php if ($unreadCount > 0): ?>
                <span class="badge badge-danger position-absolute" style="top: -5px; right: -10px; font-size: 0.7em; border-radius: 50%;">
                    <?= $unreadCount ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="home.php"><i class='bx bx-user-circle'></i></a>
    </div>
</nav>

<div class="sidebar-backdrop"></div>

<div class="sidebar">
    <div class="sidebar-content">
        <div class="tutorials-heading">TUTORIALS</div>
        <ul class="lists">
            <li class="list-group-heading">
                <a href="easy.php" class="nav-link">Easy Tutorials</a>
            </li>
            <?php if (!empty($easyCategoriesSidebar)): ?>
                <?php foreach ($easyCategoriesSidebar as $easyCategory): ?>
                    <li class="list-item"><a href="tut_part.php?category=<?= urlencode($easyCategory['category_id']); ?>" class="nav-link"><?= htmlspecialchars($easyCategory['cat_title']); ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-item"><span class="nav-link">No Easy Categories</span></li>
            <?php endif; ?>
        </ul>
        <ul class="lists">
            <li class="list-group-heading">
                <a href="meduim.php" class="nav-link">Moderate Tutorials</a>
            </li>
            <?php if (!empty($mediumCategoriesSidebar)): ?>
                <?php foreach ($mediumCategoriesSidebar as $mediumCategory): ?>
                    <li class="list-item"><a href="tut_part_medium.php?category=<?= urlencode($mediumCategory['category_id']); ?>" class="nav-link"><?= htmlspecialchars($mediumCategory['cat_title']); ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-item"><span class='nav-link'>No Medium Categories</span></li>
            <?php endif; ?>
        </ul>
        <ul class="lists">
            <li class="list-group-heading">
                <a href="hard.php" class="nav-link">Difficult Tutorials</a>
            </li>
            <?php if (!empty($hardCategoriesSidebar)): ?>
                <?php foreach ($hardCategoriesSidebar as $hardCategory): ?>
                    <li class="list-item"><a href="tut_part_hard.php?category=<?= urlencode($hardCategory['category_id']); ?>" class="nav-link"><?= htmlspecialchars($hardCategory['cat_title']); ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-item"><span class="nav-link">No Hard Categories</span></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<main class="main">
    <div class="text-black mt-3 ml-3 font-weight-bold">
        <i class="bx bx-coin-stack mr-1"></i> Coins: <span id="userCoins"><?= number_format($userCoins, 2) ?></span>
    </div>

    <div class="container">
        <a href="tut_part.php?category=<?= urlencode($category_id) ?>"
           class="btn btn-gradient mb-3 d-inline-flex align-items-center shadow-sm">
            <i class='bx bx-arrow-back icon me-2'></i> Back to Category
        </a>

        <section class="mb-4">
            <h1 class="h3 text-info mb-2"><i class="bx bx-code-alt mr-2"></i> <?= htmlspecialchars($exercise['title']) ?></h1>
            <p class="text-muted"><?= nl2br(htmlspecialchars($exercise['description'])) ?></p>
        </section>

        <section class="code-editor-container card p-3 bg-dark">
            <h5 class="text-light mb-3"><i class="bx bx-edit-alt mr-2"></i> Your Code:</h5>
            <textarea id="codeEditor" spellcheck="false" placeholder="Write your code here..."></textarea>
            <button id="sendCodeBtn" class="btn btn-submit-code mt-3 d-flex align-items-center justify-content-center">
                <i class="bx bx-send mr-2"></i> Send Code
            </button>
        </section>

        </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Sidebar and dropdown toggle logic (unchanged)
        const menuIcon = document.querySelector(".menu-icon");
        const sidebar = document.querySelector(".sidebar");
        const sidebarBackdrop = document.querySelector(".sidebar-backdrop");

        menuIcon.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            sidebarBackdrop.classList.toggle("show");
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : 'auto';
        });

        sidebarBackdrop.addEventListener("click", () => {
            sidebar.classList.remove("open");
            sidebarBackdrop.classList.remove("show");
            document.body.style.overflow = 'auto';
        });

        const dropdownToggleIcon = document.querySelector(".dropdown-toggle-icon");
        const rightDropdown = document.querySelector(".right-dropdown");

        if (dropdownToggleIcon && rightDropdown) {
            dropdownToggleIcon.addEventListener("click", (event) => {
                event.stopPropagation();
                rightDropdown.classList.toggle("show");
            });

            document.addEventListener("click", (event) => {
                if (!rightDropdown.contains(event.target) && !dropdownToggleIcon.contains(event.target)) {
                    rightDropdown.classList.remove("show");
                }
            });
        }

// Send Code Logic
$("#sendCodeBtn").on("click", function() {
    const userCode = $("#codeEditor").val();
    const exerciseId = <?= json_encode($exercise['id']) ?>; // Pass PHP variable to JS securely
    const userId = <?= json_encode($user_id) ?>; // Pass PHP variable to JS securely

    if (userCode.trim() === "") {
        alert("Please write some code before sending!");
        return;
    }

    // Disable button to prevent multiple submissions
    const $sendButton = $(this);
    $sendButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

    $.ajax({
        url: "submit_exercise_code.php", // New PHP endpoint
        method: "POST",
        data: {
            user_code: userCode,
            exercise_id: exerciseId,
            user_id: userId
        },
        dataType: "json", // Expect JSON response
        success: function(response) {
            if (response.status === "success") {
                alert("✅ Code sent successfully! An admin has been notified for review.");
                // Optional: Clear editor after successful submission
                $("#codeEditor").val('');
            } else {
                alert("❌ Error sending code: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert("❌ An error occurred during submission. Please try again.");
            console.error("AJAX Error:", status, error, xhr.responseText);
        },
        complete: function() {
            // Re-enable button after request completes
            $sendButton.prop('disabled', false).html('<i class="bx bx-send mr-2"></i> Send Code');
        }
    });
});


    });
</script>

</body>
</html>

<?php $conn = null; ?>
