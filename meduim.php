<?php
include('./conn.php'); // Include database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

// Fetch categories with user-specific completion status for "medium" difficulty
try {
    $stmt = $conn->prepare("
        SELECT
            c.id AS category_id,
            c.cat_title,
            c.cat_dis,
            (SELECT COUNT(t.id) FROM tutorial t WHERE t.tu_cat_id = c.id) AS total_tutorials,
            (
                SELECT COUNT(DISTINCT t.id)
                FROM tutorial t
                INNER JOIN parts pa ON t.part_id = pa.id
                LEFT JOIN progress pr ON pa.id = pr.part_id AND pr.user_id = :userId
                WHERE t.tu_cat_id = c.id AND pr.completed_tutorials = pr.total_tutorials AND pr.user_id = :userId
            ) AS completed_category
        FROM category c
        WHERE c.diff_id = (SELECT id FROM diff WHERE select_diff = 'medium')
        ORDER BY c.id;
    ");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<b>Error fetching categories:</b> " . $e->getMessage());
}

// ✅ Count unread notifications from the notifications table
$stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
$stmt->execute([':user_id' => $userId]);
$notification = $stmt->fetch(PDO::FETCH_ASSOC);
$unreadCount = $notification['unread_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Front End Web Dev | Medium Tutorials</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
          @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

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
            font-size: 14px;           /* Palakihin ang text */
            padding: 12px 20px;         /* Palakihin ang click area */
            font-weight: bold;          /* Gawing bold */
            color: #ffffff;             /* Pwede mo rin i-adjust color kung gusto mo */
            display: block;             /* Para mas maging buong linya ang clickable */
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

        .right-icons i {
            font-size: 24px;  /* Change to your desired size */
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

        /* Styles for the category tiles */
        <style>
    /* ... other styles ... */

    /* Styles for the category tiles - Modified for dark theme */
    .fluid-tile-container {
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Adjust min width */
        gap: 15px;
        /* Removed backdrop-filter and background-color for container */
    }

    .fluid-tile {
        background-color: #1e293b; /* Dark background color matching sidebar */
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* More subtle shadow */
        overflow: hidden;
        position: relative;
        text-decoration: none;
        color: #cbd5e1; /* Light text color */
        display: flex;
        flex-direction: column;
        padding: 20px;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
        border: 1px solid #334155; /* Subtle border */
    }

    .fluid-tile:hover {
        transform: translateY(-5px) scale(1.01); /* Less pronounced hover effect */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Slightly stronger shadow on hover */
        background-color: #2d3748; /* Slightly lighter dark background on hover */
    }

    .tile-title {
        color: #f0f0f0; /* Light title color */
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .tile-description {
        color: #a3a3a3; /* Slightly darker light description color */
        font-size: 0.9rem;
        margin-bottom: 15px;
        flex-grow: 1; /* Push complete indicator to the bottom */
    }

    .complete-indicator {
        background-color:rgb(40, 8, 137);
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 0.85rem;
        align-self: flex-start; /* Align to the top if description grows */
    }

    .tile-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.1); /* Darker overlay on hover */
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .fluid-tile:hover .tile-overlay {
        opacity: 1;
    }

    .search-bar {
        margin-bottom: 20px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
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

<div class="container mt-4">
<div class="search-bar mb-4 position-relative mx-auto" style="max-width: 500px;">
<div class="position-relative">
<input type="text" id="searchInput" class="form-control rounded-pill pl-5" placeholder="Search Categories..."
       onkeyup="filterCategories()" style="border: 1px solid #ddd; color: #333; background-color: #f8f9fa;">
<i class='bx bx-search search-icon'></i>
</div>
</div>
</div>
<h2>Medium Tutorials</h2>
<div class="fluid-tile-container">
<?php if (!empty($categories)): ?>
<?php foreach ($categories as $category): ?>
<?php
$total_tutorials = $category['total_tutorials'];
$completed_category = $category['completed_category'];
$is_complete = ($total_tutorials > 0 && $completed_category == $total_tutorials);
?>
<a href="tut_part_medium.php?category=<?= urlencode($category['category_id']); ?>" class="fluid-tile" data-title="<?= htmlspecialchars(strtolower($category['cat_title'])); ?>">
    <h3 class="tile-title"><?= htmlspecialchars($category['cat_title']); ?></h3>
    <p class="tile-description"><?= htmlspecialchars($category['cat_dis']); ?></p>
    <?php if ($is_complete): ?>
        <span class="complete-indicator">Complete</span>
    <?php endif; ?>
    <div class="tile-overlay"></div>
</a>
<?php endforeach; ?>
<?php else: ?>
<p class="text-center text-light">No categories available for "Medium" difficulty.</p>
<?php endif; ?>
</div>
</main>

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
</script>
</body>
</html>

<?php $conn = null; ?>