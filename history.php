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

// Fetch Completed Tutorial Parts
// Fetch Completed Tutorial Parts
try {
  $stmt_parts = $conn->prepare("
      SELECT
          p.part_title,
          pr.completed_tutorials,
          pr.total_tutorials,
          pr.progress_percentage,
          pr.last_updated
      FROM
          progress pr
      JOIN
          parts p ON pr.part_id = p.id
      WHERE
          pr.user_id = :user_id AND pr.completed_tutorials > 0
  ");
  $stmt_parts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt_parts->execute();
  $completed_parts = $stmt_parts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $completed_parts = [];
  // Log error: error_log("Database error fetching completed parts: " . $e->getMessage());
}


// Fetch Purchased Cards
try {
    $stmt_cards = $conn->prepare("
        SELECT
            c.title AS card_name,
            c.description AS card_description,
            pur.purchased_at AS purchase_date
        FROM
            purchases pur
        JOIN
            cards c ON pur.card_id = c.id
        WHERE
            pur.user_id = :user_id
    ");
    $stmt_cards->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_cards->execute();
    $purchased_cards = $stmt_cards->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $purchased_cards = [];
    // Log error: error_log("Database error fetching purchased cards: " . $e->getMessage());
}

// Fetch Purchased Parts
try {
  $stmt_purchased_parts = $conn->prepare("
      SELECT
          pa.part_title,
          tp.purchase_date AS purchase_date
      FROM
          tbl_purchase tp
      JOIN
          parts pa ON tp.part_id = pa.id
      WHERE
          tp.user_id = :user_id
  ");
  $stmt_purchased_parts->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt_purchased_parts->execute();
  $purchased_parts_list = $stmt_purchased_parts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $purchased_parts_list = [];
  error_log("Database error fetching purchased parts: " . $e->getMessage());
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User History | Front End Web Dev Tutorials</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
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

        /* Styles for the history content */
        .history-container {
            padding: 20px;
        }

        .history-title {
            color: #f8fafc;
            margin-bottom: 20px;
        }

        .history-section {
            margin-bottom: 30px;
            background-color: #1e293b;
            padding: 15px;
            border-radius: 4px;
        }

        .history-section-title {
            color: #cbd5e1;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            color: #a3a3a3;
        }

        .history-table th, .history-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #334155;
            text-align: left;
        }

        .history-table th {
            font-weight: bold;
            color: #64748b;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }

        .no-items {
            color: #64748b;
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
             <a href="history.php">History</a>
            <a href="about.php">Cards</a>
            <a href="setting.php">Leader board</a>
            <a href="report.php">Reports</a>
            <a href="type_to_earn.php">Earn Coins</a>
        </div>

        <i class="bx bx-dots-vertical-rounded dropdown-toggle-icon"></i>

        <div class="right-dropdown">
            <a href="history.php">History</a>
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
                <li class="list-item"><span class="nav-link">No Easy Categories</span>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="lists">
            <li class="list-group-heading">
                <a href="meduim.php" class="nav-link">Medium Tutorials</a>
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
                <a href="hard.php" class="nav-link">Hard Tutorials</a>
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
    <div class="history-container">
        <h2 class="history-title">Your Activity History</h2>

                <div class="history-section">
            <h3 class="history-section-title">Completed Tutorial Parts</h3>
            <?php if (!empty($completed_parts)): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Part Title</th>
                            <th>Completed</th>
                            <th>Total</th>
                            <th>Progress</th>
                            <th>Last Updated</th> </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completed_parts as $part): ?>
                            <tr>
                                <td><?= htmlspecialchars($part['part_title']); ?></td>
                                <td><?= htmlspecialchars($part['completed_tutorials']); ?></td>
                                <td><?= htmlspecialchars($part['total_tutorials']); ?></td>
                                <td><?= htmlspecialchars($part['progress_percentage']); ?>%</td>
                                <td><?= htmlspecialchars($part['last_updated']); ?></td> </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-items">No tutorial parts completed yet.</p>
            <?php endif; ?>
        </div>

        <div class="history-section">
            <h3 class="history-section-title">Purchased Cards</h3>
            <?php if (!empty($purchased_cards)): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Card Name</th>
                            <th>Description</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchased_cards as $card): ?>
                            <tr>
                                <td><?= htmlspecialchars($card['card_name']); ?></td>
                                <td><?= htmlspecialchars($card['card_description']); ?></td>
                                <td><?= htmlspecialchars($card['purchase_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-items">No cards purchased yet.</p>
            <?php endif; ?>
        </div>

        <div class="history-section">
          <h3 class="history-section-title">Purchased Parts</h3>
          <?php if (!empty($purchased_parts_list)): ?>
              <table class="history-table">
                  <thead>
                      <tr>
                          <th>Part Title</th>
                          <th>Purchase Date</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($purchased_parts_list as $part): ?>
                          <tr>
                              <td><?= htmlspecialchars($part['part_title']); ?></td>
                              <td><?= htmlspecialchars($part['purchase_date']); ?></td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          <?php else: ?>
              <p class="no-items">No parts purchased yet.</p>
          <?php endif; ?>
      </div>
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
