<?php
include('conn.php');
session_start();

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Count unread notifications (Pending reports)
$stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM reports WHERE user_id = :user_id AND status = 'Pending'");
$stmt->execute([':user_id' => $user_id]);
$notification = $stmt->fetch(PDO::FETCH_ASSOC);
$unreadCount = $notification['unread_count'] ?? 0;

// ✅ Get category & part safely
$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$part_id = filter_input(INPUT_GET, 'part', FILTER_VALIDATE_INT);

if (!$category_id || !$part_id) {
    header("Location: error.php?msg=Category or part not selected");
    exit();
}

// ✅ Fetch Part Details
$stmt = $conn->prepare("SELECT part_title, part_dis FROM parts WHERE id = :part_id");
$stmt->execute([':part_id' => $part_id]);
$part = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$part) {
    header("Location: error.php?msg=Part not found");
    exit();
}

// ✅ Fetch Tutorials
$stmt = $conn->prepare("
    SELECT tu_title, tut_par, code_edit, diff_part, id AS tutorial_id
    FROM tutorial
    WHERE part_id = :part_id
");
$stmt->execute([':part_id' => $part_id]);
$tutorials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch progress for this part
$stmt = $conn->prepare("SELECT completed_tutorials, total_tutorials, progress_percentage FROM progress WHERE user_id = :user_id AND part_id = :part_id");
$stmt->execute([':user_id' => $user_id, ':part_id' => $part_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

// Initialize progress if not already set
if (!$progress) {
    $progress = ['completed_tutorials' => 0, 'total_tutorials' => count($tutorials), 'progress_percentage' => 0];
}

// Determine if all tutorials are considered viewed (based on progress)
$all_viewed = ($progress['completed_tutorials'] >= $progress['total_tutorials'] && $progress['total_tutorials'] > 0);

// ✅ Count unread notifications from the notifications table
$stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
$stmt->execute([':user_id' => $user_id]);
$notification = $stmt->fetch(PDO::FETCH_ASSOC);
$unreadCount = $notification['unread_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($part['part_title']) ?> | Front End Web Dev</title>
    <link rel="stylesheet" href="css/.css" />
    <link
        href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
        rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        /* Dark Theme Styles (consistent with other pages) */
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

        /* tutorial.php Specific Styles */
        .btn-gradient {
            background: linear-gradient(to right, #007bff, #6610f2);
            color: white;
            border: none;
        }

        .btn-gradient:hover {
            background: linear-gradient(to right, #0056b3, #4c08b3);
        }

        .tutorial-section {
        background-color: #121827; /* Dark background */
        color: #e0e0e0; /* Light text */
        padding: 30px;
    }

    .back-button {
        margin-top: 80px;
        background: linear-gradient(to right, #a066f5, #50dfff);
        display: inline-flex;
        align-items: center;
        color: black;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        margin-bottom: 20px;
        transition: background-color 0.2s ease;
    }

    .back-button:hover {
        background-color: #4a5568;
    }

    .back-button .icon {
        font-size: 1.2em;
        margin-right: 8px;
    }

    .part-title {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .part-subtitle {
        color: #a3a3a3;
        margin-bottom: 20px;
    }

    .tutorial-card {
        background-color: #1e293b; /* Lighter card background */
        border: 1px solid #334155; /* Darker border */
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .tutorial-title {
        font-size: 1.3em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #f0f0f0;
    }

    .tutorial-difficulty {
        background-color: #64748b; /* Example difficulty badge color */
        color: #f0f0f0;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 0.8em;
        margin-left: 10px;
    }

    .tutorial-paragraph {
        color: #cbd5e1;
        margin-bottom: 15px;
        white-space: pre-line; /* Preserve line breaks */
    }

    .code-example-container {
    background-color: #2d3748; /* Darker code container */
    border: 1px solid #4a5568;
    border-radius: 6px;
    padding: 15px;
    margin-top: 15px;
    margin-bottom: 15px;
    overflow-x: auto; /* Add horizontal scroll for very long code */
}

pre {
    margin: 0;
    padding: 10px;
    overflow-x: auto; /* Ensure horizontal scroll for pre */
    white-space: pre-wrap; /* Allows code to wrap to the next line */
    word-break: break-word; /* Forces long words to break */
}

code {
    font-family: monospace;
    font-size: 0.9em;
    color: #d2d6e9; /* Light code text */
    display: block; /* Make code a block-level element */
    width: 100%; /* Take full width of the pre container */
}
.tutorial-card {
    background-color: #1e293b; /* Lighter card background */
    border: 1px solid #334155; /* Darker border */
    border-radius: 8px;
    padding: 15px; /* Reduced padding from 20px to 15px */
    margin-bottom: 20px;
}

    .code-example-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        color: #f0f0f0;
    }

    .code-example-title {
        font-weight: bold;
    }

    .try-it-button {
        background-color: #4f46e5; /* Primary button color */
        color: #f0f0f0;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .try-it-button:hover {
        background-color: #6366f1;
    }

    pre {
        margin: 0;
        padding: 10px;
        overflow-x: auto;
    }

    code {
        font-family: monospace;
        font-size: 0.9em;
        color: #d2d6e9; /* Light code text */
    }

    .mark-all-viewed-container {
        text-align: right;
        margin-bottom: 15px;
    }

    .mark-all-viewed-button {
        background-color: #38a169; /* Success button color */
        color: #f0f0f0;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .mark-all-viewed-button:hover {
        background-color: #48bb78;
    }

    .mark-all-viewed-button.outline {
        background-color: transparent;
        color: #38a169;
        border: 1px solid #38a169;
    }

    .mark-all-viewed-button.outline:hover {
        background-color: rgba(56, 161, 105, 0.1);
    }
    </style>
</head>

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
                    <li class="list-item"><a href="tut_part.php?category=<?= urlencode($mediumCategory['category_id']); ?>" class="nav-link"><?= htmlspecialchars($mediumCategory['cat_title']); ?></a></li>
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

<main class="main tutorial-section">



    <a href="tut_part.php?category=<?= urlencode($category_id) ?>"
       class="back-button">
        <i class='bx bx-arrow-back icon'></i> Back to Tutorials
    </a>

    <div class="container">
        <h2 class="part-title"><?= htmlspecialchars($part['part_title']) ?></h2>
        <p class="part-subtitle"><?= htmlspecialchars($part['part_dis']) ?></p>
        <hr class="mb-4" style="border-top: 1px solid #334155;">

        <div class="mark-all-viewed-container">
            <form method="POST" action="update_progress.php?category=<?= $category_id ?>&part=<?= $part_id ?>">
                <input type="hidden" name="mark_all_viewed" value="1">
                <button type="button" onclick="markAllViewed(this)" name="mark_viewed_all"
                        class="mark-all-viewed-button <?= $all_viewed ? '' : 'outline' ?>" <?= $all_viewed ? 'disabled' : '' ?>>
                    <i class='bx bx-check-circle mr-1'></i> Mark All as Viewed
                </button>
            </form>
        </div>

        <div class="tutorial-list">
            <?php foreach ($tutorials as $tutorial): ?>
                <div class="tutorial-card">
                    <h5 class="tutorial-title"><?= htmlspecialchars($tutorial['tu_title']) ?>
                        <?php if (!empty($tutorial['diff_part'])): ?>
                            <span class="tutorial-difficulty"><?= htmlspecialchars($tutorial['diff_part']) ?></span>
                        <?php endif; ?>
                    </h5>
                    <?php if (!empty($tutorial['tut_par'])): ?>
                        <p class="tutorial-paragraph"><?= nl2br(htmlspecialchars($tutorial['tut_par'])) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($tutorial['code_edit'])): ?>
                        <div class="code-example-container">
                            <div class="code-example-header">
                                <span class="code-example-title">Code Example:</span>
                                <button class="try-it-button" onclick="openEditor('<?= urlencode($tutorial['code_edit']) ?>')">Try it</button>
                            </div>
                            <pre><code class="language-html"><?= htmlspecialchars($tutorial['code_edit']) ?></code></pre>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" />
<script>
    function openEditor(code) {
        window.open("code_edit.php?code=" + code, "_blank");
    }

    function markAllViewed(button) {
        // Disable the button
        button.disabled = true;
        // Change the button's style to green
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-success');
        button.innerHTML = '<i class="bx bx-check-circle mr-1"></i> All Viewed';
        // Submit the form
        const form = button.closest('form');
        if (form) {
            form.submit();
        }
    }

    // Initialize Prism for syntax highlighting
    Prism.highlightAll();

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
</script>

</body>
</html>

<?php $conn = null; ?>