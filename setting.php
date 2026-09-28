<?php
include('./conn.php'); // Include database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Count unread notifications for the logged-in user
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $unreadCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle database error appropriately
    $unreadCount = 0;
    // Log the error: error_log("Database error fetching unread count: " . $e->getMessage());
}

// Fetch leaderboard data
try {
    $stmtLeaderboard = $conn->prepare("
        SELECT
            tbl_user_id,
            username,
            score AS total_score
        FROM admin_tb
        ORDER BY score DESC
    ");
    $stmtLeaderboard->execute();
    $leaderboard_data = $stmtLeaderboard->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $leaderboard_data = [];
    // Handle database error appropriately
    // Log the error: error_log("Database error fetching leaderboard data: " . $e->getMessage());
}

// Function to fetch user details by ID (used in modal)
function getUserDetails($conn, $userId) {
    $stmt = $conn->prepare("
        SELECT
            tbl_user_id,
            username,
            first_name,
            last_name,
            contact_number,
            email,
            profile_picture,
            score,
            coins,
            year_name,
            sec_name
        FROM admin_tb
        WHERE tbl_user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$current_user_id = $_SESSION['user_id'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Leaderboards - Web.Dev.</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        /* Dark Theme Styles */
        body {
            background-color: #0a0b0c;
            color: #e0e0e0;
        }

        nav.top-nav {
            background-color:#1f2937;
            height: 55px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 20px;
            left: 0;
            width: 100%;
            z-index: 100;
            border-bottom: 1px solid #334155;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .menu-icon {
            color: #64748b;
            font-size: 1.3em;
            cursor: pointer;
            display: none;
        }

        .logo-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #f8fafc;
            margin-left: 10px;
        }

        .sidebar {
            position: fixed;
            top: 75px;
            left: 0;
            width: 240px;
            height: calc(100vh - 75px);
            background-color: #1e293b;
            color: #e0e0e0;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 300;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-content {
            padding: 15px;
            overflow-y: auto;
            height: 100%;
        }

        .tutorials-heading {
            font-size: 0.9em;
            font-weight: bold;
            margin-bottom: 15px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding-left: 5px;
        }

        .lists {
            list-style: none;
            padding: 0;
            margin: 0 0 15px 0;
        }

        .list-group-heading {
            font-weight: bold;
            color: #cbd5e1;
            padding: 8px 15px;
            margin-bottom: 3px;
            font-size: 0.9em;
        }
        .list-group-heading .nav-link {
            font-size: 14px;
            padding: 12px 20px;
            font-weight: bold;
            color: #ffffff;
            display: block;
        }
        .list-item {
            margin-bottom: 3px;
        }

        .nav-link {
            display: block;
            color: #a3a3a3;
            text-decoration: none;
            padding: 7px 20px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
            font-size: 0.85em;
        }

        .nav-link:hover {
            background-color: #334155;
            color: #f0f0f0;
        }

        .main {
            padding: 40px 20px;
            padding-top: 75px;
            background-color: #121827;
            color:#727883;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        .main > div {
            padding: 20px;
            background-color: #121827;
            border-radius: 4px;
        }

        nav.additional-nav {
            background-color: #374151;
            height: 20px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 101;
        }

        nav.additional-nav .block {
            display: none;
        }

        .sidebar-backdrop {
            position: fixed;
            top: 75px;
            left: 0;
            width: 100%;
            height: calc(100vh - 75px);
            background: #111827;
            z-index: 250;
            display: none;
        }

        .sidebar-backdrop.show {
            display: block;
        }

        /* Right Nav Section */
        .right-icons {
            display: flex;
            align-items: center;
            position: relative;
        }

        .right-links-desktop {
            display: flex;
            gap: 10px;
        }

        .right-icons a {
            color: #64748b;
            text-decoration: none;
            margin-left: 10px;
            font-size: 0.9em;
            transition: color 0.2s ease;
        }

        .right-icons a:hover {
            color: #f8fafc;
        }

        .dropdown-toggle-icon {
            color: #f0f0f0;
            font-size: 1.5em;
            cursor: pointer;
            display: none;
            margin-left: 10px;
        }

        .right-dropdown {
            position: absolute;
            right: 40px;
            top: 45px;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 4px;
            display: none;
            flex-direction: column;
            width: 180px;
            z-index: 999;
        }
        .right-icons i {
            font-size: 24px; /* Change to your desired size */
        }

        .right-dropdown a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px;
            border-bottom: 1px solid #334155;
            display: block;
            font-size: 0.9em;
        }

        .right-dropdown a:hover {
            background-color: #334155;
        }

        .right-dropdown.show {
            display: flex;
        }

        /* Responsive Adjustments */
        @media (min-width: 769px) {
            .right-links-desktop {
                display: flex;
            }

            .dropdown-toggle-icon,
            .right-dropdown {
                display: none !important;
            }

            .sidebar {
                transform: translateX(0) !important;
            }

            .main {
                margin-left: 240px;
            }

            .menu-icon {
                display: none !important;
            }

            .sidebar-backdrop {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .right-links-desktop {
                display: none;
            }

            .dropdown-toggle-icon {
                display: block;
            }

            .menu-icon {
                display: block;
                margin-right: 10px;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Leaderboard Styles (Dark Theme) */
        .leaderboard-container {
            margin-top: 30px;
            background-color: #1e293b; /* Dark background */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Darker shadow */
            padding: 30px;
            color: #f0f0f0; /* Light text */
        }

        .leaderboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .leaderboard-header h2 {
            color: #f8f9fa; /* Light heading */
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            padding-bottom: 15px;
            display: inline-block;
        }

        .leaderboard-header h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            height: 3px;
            width: 50px;
            background-color: #007bff; /* Accent color */
        }

        .leaderboard-header p {
            color: #a3a3a3; /* Lighter secondary text */
            font-size: 1rem;
        }

        .leaderboard-list {
            list-style: none;
            padding: 0;
        }

        .leaderboard-item {
            background-color: #27374d; /* Slightly lighter dark background */
            border: 1px solid #334155; /* Darker border */
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Darker shadow */
            transition: transform 0.2s ease-in-out;
        }

        .leaderboard-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); /* Darker hover shadow */
        }

        .rank-badge {
            background-color: #007bff; /* Accent color */
            color: #fff;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            margin-right: 15px;
            font-size: 1rem;
        }

        .top-rank .rank-badge {
            background-color: gold;
            color: #333;
        }

        .second-rank .rank-badge {
            background-color: silver;
            color: #333;
        }

        .third-rank .rank-badge {
            background-color: #cd7f32; /* Bronze */
            color: #fff;
        }

        .user-info {
            flex-grow: 1;
        }

        .username {
            font-weight: 600;
            color: #f0f0f0; /* Light username */
            margin-bottom: 5px;
        }

        .score {
            color: #a3a3a3; /* Lighter score text */
            font-size: 0.9rem;
        }

        .current-user-highlight {
            background-color: #2c3e50; /* Slightly different dark highlight */
            border-left: 5px solid #007bff; /* Accent border */
        }

        .no-users {
            text-align: center;
            color: #999;
            padding: 20px;
            border: 1px solid #334155; /* Darker border */
            border-radius: 8px;
            background-color: #1e293b; /* Dark background */
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
            <?php
            // Fetch easy categories for the sidebar
            try {
                $stmtEasySidebar = $conn->prepare("
                    SELECT
                        c.id AS category_id,
                        c.cat_title
                    FROM category c
                    WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'easy')
                    ORDER BY c.id;
                ");
                $stmtEasySidebar->execute();
                $easyCategoriesSidebar = $stmtEasySidebar->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $easyCategoriesSidebar = [];
                echo "<li class='list-item'><span class='nav-link'>Error fetching Easy categories</span></li>";
            }
            ?>
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
            <?php
            // Fetch medium categories for the sidebar
            try {
                $stmtMediumSidebar = $conn->prepare("
                    SELECT
                        c.id AS category_id,
                        c.cat_title
                    FROM category c
                    WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'medium')
                    ORDER BY c.id;
                ");
                $stmtMediumSidebar->execute();
                $mediumCategoriesSidebar = $stmtMediumSidebar->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $mediumCategoriesSidebar = [];
                echo "<li class='list-item'><span class='nav-link'>Error fetching Medium categories</span></li>";
            }
            ?>
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
            <?php
            // Fetch hard categories for the sidebar
            try {
                $stmtHardSidebar = $conn->prepare("
                    SELECT
                        c.id AS category_id,
                        c.cat_title
                    FROM category c
                    WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'hard')
                    ORDER BY c.id;
                ");
                $stmtHardSidebar->execute();
                $hardCategoriesSidebar = $stmtHardSidebar->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $hardCategoriesSidebar = [];
                echo "<li class='list-item'><span class='nav-link'>Error fetching Hard categories</span></li>";
            }
            ?>
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
    <div class="container mt-5 leaderboard-container">
        <div class="leaderboard-header">
            <h2><i class='bx bx-trophy'></i> Leaderboards</h2>
            <p class="lead">See who's leading the pack in Web.Dev.!</p>
        </div>

        <?php if (!empty($leaderboard_data)): ?>
            <ul class="leaderboard-list">
                <?php $rank = 1; ?>
                <?php foreach ($leaderboard_data as $leader): ?>
                    <li class="leaderboard-item <?php
                        if ($rank <= 3) echo 'top-rank ';
                        if ($rank == 2) echo 'second-rank ';
                        if ($rank == 3) echo 'third-rank ';
                        if ($current_user_id == $leader['tbl_user_id']) echo 'current-user-highlight';
                    ?>">
                        <span class="rank-badge"><?= $rank++; ?></span>
                        <div class="user-info">
                            <p class="username"><?= htmlspecialchars($leader['username']) ?></p>
                            <small class="score">Score: <?= htmlspecialchars($leader['total_score']) ?></small>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-users">No users found in the leaderboards yet.</p>
        <?php endif; ?>
    </div>
</main>

<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userDetailsContent">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    const menuIcon = document.querySelector('.menu-icon');
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    menuIcon.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        backdrop.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : 'auto';
    });

    backdrop.addEventListener('click', () => {
        sidebar.classList.remove('open');
        backdrop.classList.remove('show');
        document.body.style.overflow = 'auto';
    });

    // Right nav dropdown
    const dropdownToggle = document.querySelector('.dropdown-toggle-icon');
    const mobileDropdown = document.querySelector('.right-dropdown');

    dropdownToggle.addEventListener('click', () => {
        mobileDropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (!dropdownToggle.contains(e.target) && !mobileDropdown.contains(e.target)) {
            mobileDropdown.classList.remove('show');
        }
    });

    function filterCategories() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const categories = document.querySelectorAll('.fluid-tile');

        categories.forEach(category => {
            const title = category.querySelector('.tile-title').textContent.toLowerCase();
            if (title.includes(filter)) {
                category.style.display = "block"; /* Ensure it's displayed in the grid */
            } else {
                category.style.display = "none";
            }
        });
    }

    $(document).ready(function() {
        $('#userDetailsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');

            // Fetch user details using AJAX
            $.ajax({
                url: 'fetch_user_details.php', // Create this file
                type: 'GET',
                data: { user_id: userId },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        var detailsHtml = `
                            <p><strong>Username:</strong> ${data.username}</p>
                            <p><strong>First Name:</strong> ${data.first_name}</p>
                            <p><strong>Last Name:</strong> ${data.last_name}</p>
                            <p><strong>Email:</strong> ${data.email}</p>
                            ${data.contact_number ? `<p><strong>Contact Number:</strong> ${data.contact_number}</p>` : ''}
                            <p><strong>Total Score:</strong> ${data.score}</p>
                            <p><strong>Coins:</strong> ${data.coins}</p>
                            ${data.year_name ? `<p><strong>Year:</strong> ${data.year_name}</p>` : ''}
                            ${data.sec_name ? `<p><strong>Section:</strong> ${data.sec_name}</p>` : ''}
                            ${data.profile_picture ? `<img src="${data.profile_picture}" alt="Profile Picture" class="img-thumbnail">` : ''}
                        `;
                        $('#userDetailsContent').html(detailsHtml);
                    } else {
                        $('#userDetailsContent').html('<p>Error fetching user details.</p>');
                    }
                },
                error: function() {
                    $('#userDetailsContent').html('<p>Error communicating with the server.</p>');
                }
            });
        });
    });
</script>
</body>
</html>

<?php $conn = null; ?>
