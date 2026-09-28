<?php
include('./conn.php'); // Include database connection
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mark notifications as read once viewed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
  $conn->prepare("UPDATE notifications SET status = 'read' WHERE user_id = :user_id")->execute([':user_id' => $user_id]);
  header("Location: achievement.php"); // Reload the page to refresh the notification count
  exit();
}

// Count unread notifications
$stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND status = 'unread'");
$stmt->execute([':user_id' => $user_id]);
$unreadCount = $stmt->fetchColumn();

// Fetch notifications for the logged-in user (exclude deleted notifications)
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = :user_id AND deleted = 0 ORDER BY created_at DESC");
$stmt->execute([':user_id' => $user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Front End Web Dev | Notifications</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            font-weight: bold;           /* Gawing bold */
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

        /* Innovative Notification Design */
       /* Innovative Notification Design for Dark Theme */
.notification-container {
    margin-top: 20px; /* Adjust top margin to account for top navigation */
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #334155; /* Darker border */
}

.notification-header h2 {
    color: #e0e0e0; /* Match main text color */
    font-weight: 600;
    margin: 0;
}

.mark-all-read-btn {
    background-color:rgb(40, 8, 137);
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.mark-all-read-btn:hover {
    background-color: #0056b3;
}

.notification-list {
    list-style: none;
    padding: 0;
}

.notification-item {
    background-color: #1e293b; /* Match sidebar background */
    border: 1px solid #334155; /* Match sidebar border */
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.notification-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.notification-content {
    flex-grow: 1;
    margin-right: 15px;
}

.notification-message {
    color: #cbd5e1; /* Light text color */
    margin-bottom: 5px;
    line-height: 1.5;
}

.notification-date {
    color: #a3a3a3; /* Slightly darker light text */
    font-size: 0.9rem;
}

.notification-status-unread {
    background-color: #111827; /* Light orange for unread */
    border-left: 5px solid #0056b3; /* Orange accent for unread */
    padding-left: 10px;
}

.close-btn {
    background: none;
    border: none;
    color: #dc3545;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.close-btn:hover {
    opacity: 1;
}

.empty-notifications {
    background-color: #1e293b; /* Match sidebar background */
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    color: #a3a3a3; /* Slightly darker light text */
    border: 1px solid #334155; /* Match sidebar border */
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
  
      <div class="container mt-5 notification-container">
          <div class="notification-header">
              <h2>Notifications</h2>
              <form method="POST">
                  <button type="submit" name="mark_all_read" class="mark-all-read-btn">
                      <i class='bx bx-check-double mr-2'></i> Mark All as Read
                  </button>
              </form>
          </div>
  
          <?php if (count($notifications) > 0): ?>
              <ul class="notification-list">
                  <?php foreach ($notifications as $notification): ?>
                      <li class="notification-item <?= $notification['status'] === 'unread' ? 'notification-status-unread' : ''; ?>">
                          <div class="notification-content">
                              <p class="notification-message"><?= htmlspecialchars($notification['message']); ?></p>
                              <small class="notification-date"><i class='bx bx-time mr-1'></i> <?= $notification['created_at']; ?></small>
                          </div>
                          <button class="close-btn" data-id="<?= $notification['id']; ?>">&times;</button>
                      </li>
                  <?php endforeach; ?>
              </ul>
          <?php else: ?>
              <div class="empty-notifications">
                  <i class='bx bx-info-circle mb-2' style="font-size: 2rem;"></i>
                  <p>No new notifications.</p>
              </div>
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
  
      document.addEventListener("DOMContentLoaded", () => {
          const closeButtons = document.querySelectorAll(".close-btn");
  
          closeButtons.forEach(button => {
              button.addEventListener("click", function() {
                  const notificationId = this.getAttribute("data-id");
  
                  fetch("delete_notification.php", {
                      method: "POST",
                      headers: {
                          "Content-Type": "application/json",
                      },
                      body: JSON.stringify({ id: notificationId })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          this.closest(".notification-item").remove(); // Remove notification item
                          // Check if the list is now empty and display the empty message
                          const notificationList = document.querySelector(".notification-list");
                          if (notificationList && notificationList.children.length === 0) {
                              const container = document.querySelector(".notification-container");
                              const emptyDiv = document.createElement("div");
                              emptyDiv.className = "empty-notifications";
                              emptyDiv.innerHTML = '<i class=\'bx bx-info-circle mb-2\' style="font-size: 2rem;"></i><p>No new notifications.</p>';
                              container.appendChild(emptyDiv);
                          }
                      }
                  })
                  .catch(error => console.error("Error:", error));
              });
          });
      });
  </script>
  </body>
  </html>
  
  <?php $conn = null; ?>