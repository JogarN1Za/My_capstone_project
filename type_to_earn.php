<?php
include('./conn.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$earnedCoins = '';
$levelNumber = isset($_GET['level']) ? max(1, (int)$_GET['level']) : 1;

// Get unread notifications
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $unreadCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    $unreadCount = 0;
    error_log("Database error fetching notifications: " . $e->getMessage());
}

// Get typing level
try {
    $stmt = $conn->prepare("SELECT * FROM typing_levels WHERE level_number = ?");
    $stmt->execute([$levelNumber]);
    $typingLevel = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $typingLevel = null;
    error_log("Database error fetching typing level: " . $e->getMessage());
}

// Redirect to first level if not found
if (!$typingLevel) {
    $stmt = $conn->query("SELECT level_number FROM typing_levels ORDER BY level_number ASC LIMIT 1");
    $firstLevel = $stmt->fetchColumn();
    if ($firstLevel) {
        header("Location: ?level=$firstLevel");
        exit();
    }
}

// Check for next level
$hasNextLevel = false;
if ($typingLevel) {
    $nextStmt = $conn->prepare("SELECT level_number FROM typing_levels WHERE level_number = ?");
    $nextStmt->execute([$typingLevel['level_number'] + 1]);
    $hasNextLevel = $nextStmt->fetchColumn();
}

// Get user coins from admin_tb
try {
    $stmt = $conn->prepare("SELECT coins FROM admin_tb WHERE tbl_user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $userCoins = $stmt->fetchColumn();
} catch (PDOException $e) {
    $userCoins = 0;
    error_log("Database error fetching user coins: " . $e->getMessage());
}

// Check if the user has already completed this level and earned coins
$levelCompletedInScore = false;
if ($typingLevel) {
    try {
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM tbl_score WHERE user_id = :user_id AND quiz_id = :level_number AND completed = 1");
        $stmtCheck->bindParam(':user_id', $user_id);
        $stmtCheck->bindParam(':level_number', $levelNumber);
        $stmtCheck->execute();
        $completedCount = $stmtCheck->fetchColumn();
        if ($completedCount > 0) {
            $levelCompletedInScore = true;
        }
    } catch (PDOException $e) {
        error_log("Database error checking score: " . $e->getMessage());
    }
}

// Handle typing submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['typed_input']) && !$levelCompletedInScore) {
    $typed = trim($_POST['typed_input']);
    $expected = trim($typingLevel['code_text']);

    if ($typed === $expected) {
        try {
            // Award coins
            $stmtUpdateCoins = $conn->prepare("UPDATE admin_tb SET coins = coins + :reward WHERE tbl_user_id = :user_id");
            $stmtUpdateCoins->execute([
                ':reward' => $typingLevel['coin_reward'],
                ':user_id' => $user_id
            ]);
            $userCoins += $typingLevel['coin_reward'];
            $earnedCoins = $typingLevel['coin_reward'];

            // Record completion in tbl_score
            $stmtInsertScore = $conn->prepare("INSERT INTO tbl_score (user_id, coin, quiz_id, completed) VALUES (:user_id, :coin, :quiz_id, 1)");
            $stmtInsertScore->execute([
                ':user_id' => $user_id,
                ':coin' => $typingLevel['coin_reward'],
                ':quiz_id' => $levelNumber
            ]);

            $_SESSION['level_completed'][$levelNumber] = true; // Optionally keep session tracking
        } catch (PDOException $e) {
            $earnedCoins = 'failed';
            error_log("Database error on submission: " . $e->getMessage());
        }
    } else {
        $earnedCoins = 'failed';
        unset($_SESSION['level_completed'][$levelNumber]); // Allow retry if incorrect
    }
}

// Check if the level is already completed (either by session or database record)
$isLevelCompleted = isset($_SESSION['level_completed'][$levelNumber]) && $_SESSION['level_completed'][$levelNumber];
$isLevelCompleted = $isLevelCompleted || $levelCompletedInScore;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Front End Web Dev | Nav Design Tutorials</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            font-size: 24px;  /* Change to your desired size */
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

       /* Style for the main container of the typing game - Matching your dark theme */
.typing-game-container {
    background-color: #22272e; /* Darker grey background */
    color: #d1d1d1;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

/* Style for the level information area */
.level-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    justify-content: space-between;
    color: #999;
    font-size: 0.95rem;
}

.level-badge {
    background-color: #007bff; /* Keep primary blue for emphasis */
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: bold;
}

.time-reward i, .reward-amount i {
    margin-right: 3px;
    color: #6c757d;
}

.reward-amount {
    color: #5cb85c; /* Success green */
    font-weight: normal;
}

/* Style for the timer - More subtle */
#timer {
    font-size: 1.2rem;
    color: #f0ad4e; /* Warning yellow */
    font-weight: bold;
    margin-bottom: 10px;
    text-align: center;
}

#timer i {
    margin-right: 5px;
}

/* Style for the feedback messages - Darker alerts */
.alert-feedback {
    margin-bottom: 10px;
    border-radius: 4px;
    padding: 8px 15px;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.alert-danger {
    background-color: #a944421a; /* Darker red background */
    border: 1px solid #a9444266;
    color: #f2dede;
}

.alert-success {
    background-color: #3c763d1a; /* Darker green background */
    border: 1px solid #3c763d66;
    color: #dff0d8;
}

.alert-feedback i {
    margin-right: 8px;
    font-size: 1rem;
}

/* Style for the code block - Darker background */
.code-block {
    background-color: #333842; /* Even darker background for code */
    color: #eee;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    font-family: 'Consolas', monospace; /* A common monospace font */
    font-size: 0.9rem;
    white-space: pre-wrap;
    overflow-x: auto;
    border: 1px solid #555;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    text-align: left;
}

/* Style for the textarea - Darker input */
#typed_input {
    background-color: #3a3f49; /* Darker input background */
    color: #fff;
    border: 1px solid #666;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

#typed_input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Style for the submit button - Matching your green */
.btn-submit {
    background-color: #28a745; /* Your green submit button */
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 15px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-submit:hover {
    background-color: #1e7e34; /* Darker green on hover */
}

.btn-submit:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

/* Style for the navigation buttons - Darker outlines */
.nav-buttons {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
}

.btn-nav {
    background-color: transparent;
    color: #999;
    border: 1px solid #666;
    border-radius: 4px;
    padding: 6px 10px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}

.btn-nav:hover {
    color: #fff;
    border-color: #fff;
}

.btn-nav i {
    margin-right: 3px;
}

.coins-container {
        display: flex;
        justify-content: center; /* Center the card */
        margin-top: 20px; /* Adjust spacing */
        margin-bottom: 20px;
    }

    .coins-card {
        background-color: #3a3f49; /* Dark background */
        color: #fff;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 15px;
        animation: floatCoin 2s ease-in-out infinite alternate; /* Subtle float animation */
    }

    .coins-icon {
        font-size: 2rem;
        color: #ffc107; /* Gold/Yellow for coins */
    }

    .coins-label {
        font-weight: bold;
    }

    .coins-amount {
        font-size: 1.2rem;
    }

    @keyframes floatCoin {
        0% { transform: translateY(0); }
        100% { transform: translateY(-5px); }
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
                    <li class="list-item"><a href="tut_part.php?category=<?= urlencode($mediumCategory['category_id']); ?>" class="nav-link"><?= htmlspecialchars($mediumCategory['cat_title']); ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-item"><span class="nav-link">No Medium Categories</span></li>
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
    <div class="container mt-5">
        <div class="coins-container">
            <div class="coins-card">
                <i class='bx bx-coin-stack coins-icon'></i>
                <span class="coins-label">Your Coins</span>
                <span class="coins-amount">₱<?= number_format((float)$userCoins, 2) ?></span>
            </div>
        </div>

        <?php if ($typingLevel): ?>
            <div class="card bg-dark text-white p-4 mb-3">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="mr-3"><span class="badge badge-primary rounded-pill px-3 py-2">Level <?= $typingLevel['level_number'] ?></span></h4>
                    <span class="text-muted"><i class='bx bx-timer align-middle mr-1'></i> Time: <?= (int)$typingLevel['time_limit'] ?>s</span>
                    <span class="ml-auto text-success"><i class='bx bx-award align-middle mr-1'></i> Reward: ₱<?= number_format($typingLevel['coin_reward'], 2) ?></span>
                </div>
                <p id="timer" class="lead mb-2"><i class='bx bx-hourglass-alt align-middle mr-2'></i> Time Left: <?= $typingLevel['time_limit'] ?>s</p>

                <?php if ($earnedCoins === 'failed'): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class='bx bx-x-circle mr-2'></i> ❌ Incorrect! Try again.
                    </div>
                <?php elseif ($earnedCoins): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class='bx bx-check-circle mr-2'></i> ✅ Correct! You earned ₱<?= number_format($earnedCoins, 2) ?>.
                    </div>
                <?php endif; ?>

                <div class="bg-secondary rounded p-3 mb-3" style="user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; text-align: left; font-family: monospace; font-size: 1rem; border: 1px solid #444;">
                    <pre class="text-white mb-0"><?= htmlspecialchars($typingLevel['code_text']) ?></pre>
                </div>

                <form method="POST" onsubmit="return validateBeforeSubmit();">
                    <div class="form-group mt-3">
                        <textarea id="typed_input" name="typed_input" class="form-control form-control-lg" rows="5" required <?= $isLevelCompleted ? 'disabled' : '' ?> placeholder="Type the code here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg" <?= $isLevelCompleted ? 'disabled' : '' ?>>Submit</button>
                </form>

                <div class="mt-4 d-flex justify-content-between">
                    <?php if ($typingLevel['level_number'] > 1): ?>
                        <a href="?level=<?= $typingLevel['level_number'] - 1 ?>" class="btn btn-outline-light btn-sm"><i class='bx bx-left-arrow-alt align-middle mr-1'></i> Previous</a>
                    <?php endif; ?>
                    <?php if ($hasNextLevel): ?>
                        <a href="?level=<?= $typingLevel['level_number'] + 1 ?>" class="btn btn-outline-light btn-sm"><i class='bx bx-right-arrow-alt align-middle mr-1'></i> Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">⚠ Level not found. Please contact the admin.</div>
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

    let timeLeft = <?= (int)$typingLevel['time_limit'] ?>;
    const timer = document.getElementById("timer");
    const input = document.getElementById("typed_input");

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timer.textContent = "⏰ Time's up!";
            input.disabled = true;
            document.querySelector("button[type='submit']").disabled = true;
        } else {
            timer.textContent = "⏱ Time Left: " + timeLeft + "s";
        }
        timeLeft--;
    }, 1000);

    function validateBeforeSubmit() {
        return !input.disabled;
    }
</script>

</body>
</html>

<?php $conn = null; ?>