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

$success = '';
$error = '';
$purchasedCard = null;

// Fetch user coins
$userCoinsStmt = $conn->prepare("SELECT coins FROM admin_tb WHERE tbl_user_id = ?");
$userCoinsStmt->execute([$user_id]);
$userCoins = $userCoinsStmt->fetchColumn() ?: 0;

// Fetch user name
$stmt = $conn->prepare("SELECT first_name, last_name FROM admin_tb WHERE tbl_user_id = :user_id");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
$userName = $userData['first_name'] . ' ' . $userData['last_name'];

// Handle purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modal_card_id'])) {
    $card_id = (int)$_POST['modal_card_id'];
    $cardDetailsStmt = $conn->prepare("SELECT * FROM cards WHERE id = ?");
    $cardDetailsStmt->execute([$card_id]);
    $cardDetails = $cardDetailsStmt->fetch(PDO::FETCH_ASSOC);

    if ($cardDetails) {
        $cardPrice = $cardDetails['price'];
        if ($cardPrice > $userCoins) {
            $error = "Not enough coins.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM purchases WHERE user_id = ? AND card_id = ?");
            $stmt->execute([$user_id, $card_id]);
            if ($stmt->rowCount() > 0) {
                $error = "Already purchased.";
            } else {
                $conn->beginTransaction();
                $deductCoins = $conn->prepare("UPDATE admin_tb SET coins = coins - ? WHERE tbl_user_id = ?");
                $insertPurchase = $conn->prepare("INSERT INTO purchases (user_id, card_id) VALUES (?, ?)");
                if ($deductCoins->execute([$cardPrice, $user_id]) && $insertPurchase->execute([$user_id, $card_id])) {
                    $conn->commit();
                    $success = "Purchased successfully!";
                    $userCoins -= $cardPrice;
                    $purchasedCard = $cardDetails;
                } else {
                    $conn->rollBack();
                    $error = "Purchase failed.";
                }
            }
        }
    }
}

// Get all cards
$stmt = $conn->prepare("SELECT * FROM cards ORDER BY created_at DESC");
$stmt->execute();
$allCards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user purchases for checking ownership
$purchasedCardsStmt = $conn->prepare("SELECT card_id FROM purchases WHERE user_id = ?");
$purchasedCardsStmt->execute([$user_id]);
$purchased = $purchasedCardsStmt->fetchAll(PDO::FETCH_COLUMN);

// Define rarity order and colors
$rarityOrder = [
    'Common' => ['#CD7F32', '#C0C0C0'],
    'Uncommon' => ['#FFD700'],
    'Rare' => ['#E5E4E2'],
    'Epic' => ['#50C878'],
    'Legendary' => ['#B9F2FF'],
];

// Group all cards by rarity
$cardsByRarity = [];
foreach ($allCards as $card) {
    $rarity = null;
    foreach ($rarityOrder as $key => $colors) {
        if (in_array($card['color'], $colors)) {
            $rarity = $key;
            break;
        }
    }
    if ($rarity) {
        $cardsByRarity[$rarity][] = $card;
    }
}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Front End Web Dev | Hard Tutorials</title>
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
            font-size: 14px;         /* Palakihin ang text */
            padding: 12px 20px;         /* Palakihin ang click area */
            font-weight: bold;         /* Gawing bold */
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

        /* Styles for the category tiles */
        .innovative-cards-container {
            padding: 20px;
        }

        .innovative-cards-title {
            color: #cbd5e1;
            margin-bottom: 20px;
            text-align: center;
        }

        .innovative-coins {
            background-color: #1e293b;
            color: #a3a3a3;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .innovative-coins i {
            color: #facc15;
        }

        .coin-amount {
            color: #86efac;
        }

        .coin-unit {
            font-size: 0.8em;
        }

        .innovative-cards-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .purchased-card {
            background-color: #1e293b;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            width: 280px;
            display: flex;
            flex-direction: column;
        }

        .purchased-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: var(--card-color, #334155);
            color: #111827;
            padding: 15px;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-right: 10px;
        }

        .card-header h4 {
            margin: 0;
            font-size: 1.1em;
        }

        .card-body {
            padding: 15px;
        }

        .description {
            color: #a3a3a3;
            font-size: 0.9em;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .card-price-innovative {
            background-color: #27374d;
            color: #86efac;
            padding: 10px 15px;
            border-top: 1px solid #334155;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 0.9em;
        }

        .card-price-innovative i {
            color: #facc15;
            margin-left: 5px;
        }

        .card-actions-innovative {
            padding: 10px 15px;
            display: flex;
            justify-content: flex-end;
        }

        .innovative-view-btn {
            background-color: #3b82f6;
            color: #f8fafc;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8em;
            transition: background-color 0.2s ease-in-out;
        }

        .innovative-view-btn:hover {
            background-color: #2563eb;
        }

        .purchased-badge {
            background-color: #4ade80;
            color: #1e293b;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 0.8em;
        }

        .empty-state {
            color: #a3a3a3;
            text-align: center;
            padding: 20px;
        }

        .empty-state i {
            font-size: 1.5em;
            margin-bottom: 10px;
            display: block;
        }

        .level_colors {
            background-color: #1e293b;
            color: #a3a3a3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9em;
        }

        .level_colors h2 {
            color: #cbd5e1;
            font-size: 1.1em;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .level_colors ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .level_colors li {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .level_colors span {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
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
<div class="container text-center innovative-cards-container">
    <h2 class="mb-4 innovative-cards-title">Explore Available Licenses</h2>
    <div class="level_colors">
        <h2>Level Colors</h2>
        <p style="font-size: 0.9em; color: #a3a3a3; margin-bottom: 10px;">(Ordered from most common to rarest)</p>
        <ul style="list-style: none; padding-left: 0; display: flex; gap: 20px; align-items: center; justify-content: center; flex-wrap: wrap;">
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #CD7F32;"></span> Bronze
            </li>
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #C0C0C0;"></span> Silver
            </li>
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #FFD700;"></span> Gold
            </li>
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #E5E4E2;"></span> Platinum
            </li>
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #50C878;"></span> Emerald
            </li>
            <li style="display: flex; align-items: center; gap: 5px;">
                <span style="background-color: #B9F2FF;"></span> Diamond
            </li>
        </ul>
    </div>
    <div class="coins-display innovative-coins">
        <i class="bx bx-coin-stack"></i> <span class="total-coins">Total Coins:</span>
        <span class="coin-amount"><?= htmlspecialchars($userCoins) ?></span>
        <span class="coin-unit">Coins</span>
    </div>
    <?php foreach ($rarityOrder as $rarity => $colors): ?>
        <section class="achievements-section">
            <h2 style="color: #cbd5e1; text-align: left; margin-bottom: 15px;"><?= htmlspecialchars($rarity) ?> Licenses</h2>
            <div class="innovative-cards-grid">
                <?php if (!empty($cardsByRarity[$rarity])): ?>
                    <?php foreach ($cardsByRarity[$rarity] as $card): ?>
                        <div class="purchased-card" style="--card-color: <?= htmlspecialchars($card['color'] ?? '#334155') ?>;">
                            <div class="card-header">
                                <i class='bx bx-award'></i>
                                <h4><?= htmlspecialchars($card['title']) ?></h4>
                            </div>
                            <div class="card-body">
                                <p class="description"><?= htmlspecialchars($card['description']) ?></p>
                                <?php if ($card['expiration_date']): ?>
                                    <p style="color: #a3a3a3; font-size: 0.8em; margin-bottom: 5px;">
                                        <i class='bx bx-calendar-x'></i> Expires: <?= htmlspecialchars(date('Y-m-d', strtotime($card['expiration_date']))) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-price-innovative">
                                <i class="bx bx-coin"></i> <span><?= htmlspecialchars($card['price']) ?></span>
                            </div>
                            <div class="card-actions-innovative">
                                <?php if (in_array($card['id'], $purchased)): ?>
                                    <div class="purchased-badge"><i class='bx bx-check-circle'></i> Purchased</div>
                                <?php else: ?>
                                    <button class="btn innovative-view-btn purchase-btn"
                                            data-card-id="<?= $card['id'] ?>"
                                            data-card-title="<?= htmlspecialchars($card['title']) ?>"
                                            data-card-description="<?= htmlspecialchars($card['description']) ?>"
                                            data-card-color="<?= htmlspecialchars($card['color'] ?? '#334155') ?>"
                                            data-card-price="<?= htmlspecialchars($card['price']) ?>"
                                            <?php if ($card['expiration_date']): ?>
                                                data-card-expiration="<?= htmlspecialchars(date('Y-m-d', strtotime($card['expiration_date']))) ?>"
                                            <?php else: ?>
                                                data-card-expiration=""
                                            <?php endif; ?>
                                    >
                                        View Details
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class='bx bx-info-circle'></i> No <?= htmlspecialchars($rarity) ?> licenses available.
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>

</main>

<div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title" id="purchaseModalLabel" style="color: #cbd5e1;">Purchase Card</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="purchased-card" id="modalCardWrapper" style="--card-color: #334155;">
                    <div class="card-header" id="modalCardHeader">
                        <i class='bx bx-award'></i>
                        <h4 id="modalCardTitle"></h4>
                    </div>
                    <div class="card-body">
                        <p class="description" id="modalCardDescription"></p>
                        <p style="color: #a3a3a3; font-size: 0.9em; margin-bottom: 5px;" id="modalCardExpiration"></p>
                        <p style="color: #a3a3a3; font-size: 0.9em; margin-bottom: 10px;">Are you sure you want to purchase this license?</p>
                        <p style="color: #86efac; font-weight: bold;"><i class="bx bx-coin"></i> Price: <span id="modalCardPrice"></span> Coins</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #334155;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="about.php">
                    <input type="hidden" name="modal_card_id" id="modalCardId">
                    <button type="submit" class="btn btn-primary">Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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

    document.addEventListener('DOMContentLoaded', () => {
        const purchaseButtons = document.querySelectorAll('.purchase-btn');
        const purchaseModalElement = document.getElementById('purchaseModal');
        const purchaseModal = new bootstrap.Modal(purchaseModalElement);
        const modalCardExpirationElement = document.getElementById('modalCardExpiration');

        purchaseButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                const cardId = button.getAttribute('data-card-id');
                const cardTitle = button.getAttribute('data-card-title');
                const cardDescription = button.getAttribute('data-card-description');
                const cardColor = button.getAttribute('data-card-color');
                const cardPrice = button.getAttribute('data-card-price');
                const cardExpiration = button.getAttribute('data-card-expiration');

                // Set modal content
                document.getElementById('modalCardTitle').textContent = cardTitle;
                document.getElementById('modalCardDescription').textContent = cardDescription;
                document.getElementById('modalCardId').value = cardId;
                document.getElementById('modalCardPrice').textContent = cardPrice;

                // Set the background color of the modal card header
                document.getElementById('modalCardHeader').style.backgroundColor = cardColor;

                // Handle expiration date display in the modal
                if (cardExpiration) {
                    modalCardExpirationElement.textContent = `Expires: ${cardExpiration}`;
                } else {
                    modalCardExpirationElement.textContent = ''; // Clear if no expiration date
                }

                // Show the modal
                purchaseModal.show();
            });
        });
    });
</script>
</body>
</html>

<?php $conn = null; ?>
