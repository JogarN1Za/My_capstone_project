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
    $stmtNotifications = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND status = 'unread' AND deleted = 0");
    $stmtNotifications->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtNotifications->execute();
    $unreadCount = $stmtNotifications->fetchColumn();
} catch (PDOException $e) {
    $unreadCount = 0;
    // Log the error: error_log("Database error fetching unread count: " . $e->getMessage());
}

// Fetching user data (same as before)
$stmtUser = $conn->prepare("
    SELECT
        a.first_name,
        a.last_name,
        a.username,
        g.year_level,
        s.section_name,
        a.score,
        a.coins,
        a.profile_picture,
        a.email,
        a.contact_number
    FROM
        admin_tb a
    LEFT JOIN
        grades g ON a.year_id = g.id
    LEFT JOIN
        sections s ON a.sec_id = s.id
    WHERE
        a.tbl_user_id = :user_id
");

$stmtUser->execute([':user_id' => $_SESSION['user_id']]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    $score = $userData['score'] ?? 0;
    $coins = $userData['coins'] ?? 0.00;
    $profilePicture = $userData['profile_picture'] ?? 'profile-placeholder.jpg'; // Default image
    $section = $userData['section_name'] ?? 'N/A';
    $yearLevel = $userData['year_level'] ?? 'N/A';
    $userName = $userData['first_name'] . ' ' . $userData['last_name'];
    $userEmail = $userData['email'];
    $userYear = $yearLevel; // Assign value to $userYear
    $userSection = $section; // Assign value to $userSection
    $_SESSION['first_name'] = $userData['first_name'];
    $_SESSION['last_name'] = $userData['last_name'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['contact_number'] = $userData['contact_number'];
} else {
    // Handle the case where user data is not found
    echo "Error: Could not retrieve user data.";
    exit();
}

// Ensure the profile picture path is correct
$profilePicturePath = 'admin/uploads/' . htmlspecialchars($profilePicture);

// Fetch purchased cards for the logged-in user
$purchasedCardsStmt = $conn->prepare("
    SELECT
        c.title AS card_name,
        c.description AS card_description,
        c.color,
        c.expiration_date -- Assuming expiration_date is in the cards table
    FROM purchases p
    JOIN cards c ON p.card_id = c.id
    WHERE p.user_id = :user_id
");
$purchasedCardsStmt->execute([':user_id' => $_SESSION['user_id']]);
$purchasedCards = $purchasedCardsStmt->fetchAll(PDO::FETCH_ASSOC);

$rarityOrder = [
    'Common' => ['#CD7F32', '#C0C0C0'],
    'Uncommon' => ['#FFD700'],
    'Rare' => ['#E5E4E2'],
    'Epic' => ['#50C878'],
    'Legendary' => ['#B9F2FF'],
];

$purchasedCardsByRarity = [];
foreach ($purchasedCards as $card) {
    $rarity = null;
    foreach ($rarityOrder as $key => $colors) {
        if (in_array($card['color'], $colors)) {
            $rarity = $key;
            break;
        }
    }
    if ($rarity) {
        $purchasedCardsByRarity[$rarity][] = $card;
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
            /* Adjust main styling as needed to accommodate the old content */
        }

        .main > div {
            /* Basic styling for the welcome message (you might remove this) */
            /* padding: 20px;
            background-color: #1e293b;
            border-radius: 8px;
            margin-bottom: 20px;
            width: 100%; */
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

        /* Styles from old home.php */
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .header-logo {
            max-height: 50px;
            margin-right: 15px;
            vertical-align: middle;
        }

        .header-title {
            display: inline-block;
            font-size: 2rem;
            color: #333;
            vertical-align: middle;
            margin: 0;
        }

      /* Modified Profile Overview Section */
.profile-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
    padding: 20px;
    background-color:rgb(27, 34, 46); /* Darker background */
    border-radius: 8px; /* Optional: Add some rounding */
}

.profile-card {
    background-color:rgb(10, 23, 45); /* Slightly lighter dark background */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Darker shadow */
    overflow: hidden;
    color: #f0f0f0; /* Light text color */
}

.profile-card .profile-header {
    background-color:rgb(31, 42, 52); /* Darker header */
    padding: 30px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgb(31, 42, 52); /* Darker border */
}

.profile-card .profile-pic {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    margin-right: 20px;
    border: 3px solid #4b5d67; /* Darker border */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Darker shadow */
}

.profile-card .user-info h2.user-name {
    font-size: 1.5rem;
    color: #f8f9fa; /* Light text */
    margin-bottom: 5px;
}

.profile-card .user-info p.user-details {
    color: #a3a3a3; /* Slightly lighter secondary text */
    font-size: 0.9rem;
}

.profile-card .user-info p.user-details span {
    margin-right: 15px;
}

.profile-card .user-info p.user-details i {
    margin-right: 5px;
    color: #64748b; /* Muted icon color */
}

.profile-card .profile-body {
    padding: 25px;
}

.profile-card .contact-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.profile-card .contact-info li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    color: #d1d5db; /* Light contact info text */
}

.profile-card .contact-info li i {
    margin-right: 10px;
    font-size: 1.1rem;
    color: #64748b; /* Muted icon color */
}

/* Modified Your Progress Section */
.progress-section {
    background-color:rgb(31, 42, 52); /* Darker background */
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Darker shadow */
    padding: 30px;
    margin-bottom: 30px;
    color: #f0f0f0; /* Light text color */
}

.progress-section h3 {
    font-size: 1.5rem;
    color: #f8f9fa; /* Light heading */
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 1px solid #4b5d67; /* Darker border */
    padding-bottom: 15px;
}

.progress-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.progress-card {
    background-color:rgba(0, 0, 0, 0.2); /* Slightly lighter dark background */
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
}

.progress-card .icon-wrapper {
    background-color: rgb(10, 23, 45); /* Slightly more prominent blue accent */
    color: #007bff;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    margin-right: 20px;
}

.progress-card.coins .icon-wrapper {
    background-color: rgb(10, 23, 45); /* Slightly more prominent yellow accent */
    color: #ffc107;
}

.progress-card .progress-info {
    flex-grow: 1;
}

.progress-card .progress-info .label {
    font-size: 0.9rem;
    color: #a3a3a3; /* Slightly lighter secondary text */
    margin-bottom: 5px;
}

.progress-card .progress-info .value {
    font-size: 1.3rem;
    font-weight: bold;
    color: #d1d5db; /* Light value text */
    margin-bottom: 8px;
}

.progress-card .progress-info .progress-bar-container {
    background-color: #4b5d67; /* Darker progress bar background */
    border-radius: 5px;
    height: 8px;
    overflow: hidden;
    position: relative;
}

.progress-card .progress-info .progress-bar {
    background-color: #007bff;
    height: 100%;
    border-radius: 5px;
}

.progress-card.coins .progress-info .progress-bar {
    background-color: #ffc107;
}

.progress-card.coins .progress-info .coin-icon {
    position: absolute;
    right: -15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.2rem;
    color: #ffc107;
}


            /*container*/
        .card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            max-width: 300px;
            margin: 15px; /* Add margin to create spacing between cards */
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .row.g-3 {
            gap: 20px; /* Add gap between rows and columns */
        }
        .card {
            position: relative;
            background: linear-gradient(135deg, #f9f9f9, #fff); /* Subtle diagonal gradient */
            border-radius: 12px; /* Slightly more rounded */
            padding: 25px; /* Increased padding */
            width: 100%;
            color: #333;
            margin: 0 auto;
            word-break: break-word;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Enhanced shadow */
            transition: transform 0.2s ease-in-out; /* Add subtle hover effect */
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .corner-deco {
            position: absolute;
            top: 0;
            right: 0;
            width: 60px; /* Slightly larger */
            height: 60px;
            clip-path: polygon(100% 0, 0% 0%, 100% 100%);
            border-top-right-radius: 12px;
        }

        h5.card-title {
            font-size: 1.1rem;
            color: #777;
            margin-bottom: 10px;
        }

        h5.card-title i {
            margin-right: 8px;
        }

        h1 {
            font-size: 1.8rem; /* More prominent title */
            line-height: 1.4;
            margin-bottom: 15px;
            text-align: left;
            color: #222;
        }

        p {
            font-size: 0.95rem;
            color: #555;
            text-align: left;
            margin-bottom: 10px;
            line-height: 1.6; /* Improved readability */
        }

        p span {
            color: #007bff;
            font-size: 0.85rem;
            font-weight: bold;
            margin-right: 5px;
        }

        .icons {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-top: 20px;
            position: relative;
            bottom: auto;
            right: auto;
        }

        .icons i {
            color: #444;
            font-size: 26px; /* Slightly larger icons */
        }

        /* Purchased Cards Section (Horizontal Scroll) */
        .purchased-cards-section {
            background-color: #111827;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .purchased-cards-section h2 {
            font-size: 1.5rem;
            color: #fff;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

            .purchased-cards-row {
            display: flex;
            flex-wrap: nowrap; /* Prevent cards from wrapping */
            overflow-x: auto; /* Enable horizontal scrolling */
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
            padding-bottom: 15px; /* For scrollbar visibility */
        }

        .purchased-card {
            background-color: #1e293b;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            width: 300px;
            display: flex;
            flex-direction: column;
            flex: 0 0 auto; /* Don't grow/shrink, take auto width */
            width: 300px; /* Adjust card width as needed */
            margin-right: 10px; /* Space between cards */
        }

        .purchased-card:hover {
            transform: translateY(-5px);
        }

        .purchased-card .card-header {
            background-color: var(--card-color, #334155);
            color: #111827;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .purchased-card .card-header i {
            font-size: 1.3rem;
            margin-right: 10px;
        }

        .purchased-card .card-header h4 {
            font-size: 1.1rem;
            margin: 0;
        }

        .purchased-card .card-body {
            padding: 20px;
        }

        .purchased-card .card-body .description {
            color: #a3a3a3;
            font-size: 0.9em;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .purchased-card .card-body .user-details {
            list-style: none;
            padding: 0;
            margin-bottom: 15px;
            color:  #a3a3a3;
            font-size: 0.9rem;
        }

        .purchased-card .card-body .user-details li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .purchased-card .card-body .user-details li i {
            margin-right: 8px;
            font-size: 1rem;
        }

        .purchased-card .card-body .tech-icons {
            display: flex;
            gap: 15px;
            font-size: 1.5rem;
            color: #999;
        }

        /* Static Achievements Section (Kept as Grid) */
        .static-achievements-section {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .static-achievements-section h2 {
            font-size: 1.5rem;
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .static-achievements-section .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .static-achievements-section .achievement-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .static-achievements-section .achievement-card:hover {
            transform: translateY(-5px);
        }

        .static-achievements-section .achievement-card .card-icon {
            background-color: rgba(0, 0, 0, 0.03);
            padding: 20px;
            text-align: center;
            font-size: 2rem;
            color: #777;
        }

        .static-achievements-section .achievement-card.static .card-icon {
            color: inherit; /* Inherit color from text */
        }

        .static-achievements-section .achievement-card h3 {
            padding: 15px 20px;
            margin: 0;
            font-size: 1.1rem;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .static-achievements-section .achievement-card p {
            padding: 15px 20px;
            margin: 0;
            color: #555;
            line-height: 1.6;
        }

        .empty-state {
            padding: 20px;
            text-align: center;
            color: #777;
            font-style: italic;
        }
                /* Style for the level color legend */
                .level-legend-section {
                    background-color: #fff;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                    margin-bottom: 30px;
                }

                .level-legend-section h3 {
                    font-size: 1.5rem;
                    color: #333;
                    margin-top: 0;
                    margin-bottom: 15px;
                    border-bottom: 2px solid #eee;
                    padding-bottom: 10px;
                }

                .rarity-row {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    margin-bottom: 10px;
                }

                .rarity-label {
                    font-weight: bold;
                    color: #555;
                    width: 120px; /* Adjust width as needed */
                }

                .color-item {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    margin-right: 15px;
                }

                .color-box {
                    display: inline-block;
                    width: 20px;
                    height: 20px;
                    border-radius: 3px;
                    border: 1px solid #ccc;
                }

                .color-name {
                    font-size: 0.9em;
                    color: #777;
                }

                    /* Basic styling for the modal card within the modal */
       /* Existing modal styles */
.modal-content {
        background-color: transparent;
        border: none;
    }

    /* Style to make the modal body look like the purchased card */
    .modal-body.modal-card-style {
        background-color: #1e293b;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding: 20px; /* Add some padding back to the body */
        align-items: center; /* Center content horizontally */
        text-align: center; /* Center text within elements */
    }

    /* Style for the modal header */
    .modal-header.modal-card-header-style {
        background-color: #a3a3a3;
        color: #111827;
        padding: 15px 20px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        border-bottom: 1px solid #475569; /* Optional: Add a separator */
        display: flex;
        justify-content: center; /* Center the h5 */
    }

    .modal-header.modal-card-header-style h5 {
        display: flex;
        align-items: center;
        margin: 0; /* Reset default h5 margin */
        font-size: 1.25rem; /* Adjust as needed */
    }

    .modal-header.modal-card-header-style h5 i {
        font-size: 1.5rem; /* Adjust icon size */
        margin-right: 10px;
    }

    .modal-header.modal-card-header-style h5 .modal-title {
        margin: 0; /* Reset inner h5 margin */
        font-size: inherit; /* Inherit size from parent h5 */
    }

    /* Style for the card header content within the modal (if still needed) */
    .modal-body.modal-card-style .card-header.modal-card-header-content {
        background-color: var(--card-color, #334155);
        color: #f8fafc;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        margin-bottom: 20px; /* Add some space below */
    }

    .modal-body.modal-card-style .card-header.modal-card-header-content i {
        font-size: 1.3rem;
        margin-right: 10px;
    }

    /* Style for the card body content within the modal */
    .modal-body.modal-card-style .card-body.modal-card-body-content {
        padding: 0; /* Adjust padding as needed */
    }

    .modal-body.modal-card-style .description {
        color: #a3a3a3;
        font-size: 0.9em;
        margin-bottom: 10px;
        line-height: 1.5;
    }

    .modal-body.modal-card-style .user-details.modal-card-user-details {
        list-style: none;
        padding: 0;
        margin-bottom: 15px;
        color: #a3a3a3;
        font-size: 0.9rem;
    }

    .modal-body.modal-card-style .user-details.modal-card-user-details li {
        margin-bottom: 5px;
        display: flex;
        align-items: left;
        justify-content: left; /* Center list items */
    }

    .modal-body.modal-card-style .user-details.modal-card-user-details li i {
        margin-right: 8px;
        font-size: 1rem;
    }

    .modal-body.modal-card-style .tech-icons.modal-card-tech-icons {
        display: flex;
        gap: 15px;
        font-size: 1.5rem;
        color: #999;
        justify-content: non; /* Center icons */
    }

    /* Style for the modal footer to align buttons */
    .modal-footer {
        background-color: #2c3e50; /* Optional: Darker footer */
        border-top: 1px solid #34495e;
        padding: 15px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        display: flex;
        justify-content: center; /* Center the close button */
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
            <?php
            if ($unreadCount > 0): ?>
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

<div class="profile-overview">
<div class="profile-card">
<div class="profile-header">
    <div class="profile-pic" style="background-image: url('<?= $profilePicturePath ?>');"></div>
    <div class="user-info">
        <h2 class="user-name"><?= htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']) ?></h2>
        <p class="user-details">
            <span class="username"><i class='bx bx-user'></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
            <span class="level"><i class='bx bx-briefcase'></i> <?= htmlspecialchars($yearLevel) ?> - <?= htmlspecialchars($section) ?></span>
        </p>
    </div>
</div>
<div class="profile-body">
    <ul class="contact-info">
        <li><i class='bx bx-envelope'></i> <?= htmlspecialchars($_SESSION['email']) ?></li>
        <li><i class='bx bx-phone'></i> <?= htmlspecialchars($_SESSION['contact_number'] ?? 'N/A') ?></li>
    </ul>
</div>
</div>

<div class="progress-section">
<h3>Your Progress</h3>
<div class="progress-cards">
    <div class="progress-card score">
        <div class="icon-wrapper">
            <i class='bx bx-star'></i>
        </div>
        <div class="progress-info">
            <span class="label">Total Score</span>
            <span class="value"><?= htmlspecialchars($score) ?> Points</span>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: <?= min(100, ($score / 100) * 100) ?>%;"></div>
            </div>
        </div>
    </div>
            <div class="progress-card coins">
                <div class="icon-wrapper">
                    <i class='bx bx-coin-stack'></i>
                </div>
                <div class="progress-info">
            <span class="label">Total Coins</span>
            <span class="value"><?= htmlspecialchars(number_format($coins, 2)) ?> Coins</span>
            <div class="progress-bar-container">
                <?php
                // Define the maximum coin value for a full progress bar
                $maxCoins = 100; // Adjust this value based on your game's design

                // Calculate the progress percentage
                $progressPercentage = ($coins / $maxCoins) * 100;

                // Ensure the percentage is within the valid range (0-100)
                $progressPercentage = max(0, min(100, $progressPercentage));
                ?>
                <div class="progress-bar" style="width: <?= $progressPercentage ?>%; background-color: #ffc107;"></div>
                <span class="coin-icon"><i class='bx bxs-star'></i></span>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="profile-section">
    <h3>Certificates</h3>
    <?php foreach ($rarityOrder as $rarity => $colors): ?>
        <section class="achievements-section purchased-cards-section">
            <h2>Your Purchased Achievements & Certificates for <?= htmlspecialchars($rarity) ?></h2>
            <?php if (!empty($purchasedCardsByRarity[$rarity])): ?>
                <div class="purchased-cards-row">
                    <?php foreach ($purchasedCardsByRarity[$rarity] as $card): ?>
                        <div class="purchased-card">
                            <div class="card-header" style="background-color: <?= htmlspecialchars($card['color'] ?? array_values($colors)[0]) ?>;">
                                <i class='bx bx-award'></i>
                                <h4><?= htmlspecialchars($card['card_name']) ?></h4>
                            </div>
                            <div class="card-body">
                                <p class="description"><?= htmlspecialchars($card['card_description']) ?></p>
                                <ul class="user-details">
                                 <li><i class='bx bx-user'></i> <?= htmlspecialchars($userName) ?></li>
                                    <li><i class='bx bx-calendar'></i> <?= date('M'.'D'.'Y') ?></li>
                                    <li><i class='bx bx-label'></i> Web Dev</li>
                                    <li><i class='bx bx-envelope'></i> <?= htmlspecialchars($userEmail) ?></li>
                                    <?php if (isset($card['expiration_date'])): ?>
                                    <li><i class='bx bx-time'></i> Expires on: <?= htmlspecialchars(date('M d, Y', strtotime($card['expiration_date']))) ?></li>
                                    <?php endif; ?>

                                </ul>
                                <div class="tech-icons">
                                    <i class="bx bxl-html5"></i>
                                    <i class="bx bxl-css3"></i>
                                    <i class="bx bxl-javascript"></i>
                                </div>
                                    <button type="button" class="btn btn-primary btn-sm mt-3 view-save-btn" data-toggle="modal" data-target="#cardModal"
                                    data-card-name="<?= htmlspecialchars($card['card_name']) ?>"
                                    data-card-description="<?= htmlspecialchars($card['card_description']) ?>"
                                    data-user-name="<?= htmlspecialchars($userName) ?>"
                                    data-user-email="<?= htmlspecialchars($userEmail) ?>"
                                    data-card-color="<?= htmlspecialchars($card['color'] ?? array_values($colors)[0]) ?>"
                                    <?php if (isset($card['expiration_date'])): ?>
                                    data-expiration-date="<?= htmlspecialchars(date('M d, Y', strtotime($card['expiration_date']))) ?>"
                                    <?php endif; ?>
                                    > View
                                    </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class='bx bx-info-circle'></i> No purchased achievements or certificates yet for <?= htmlspecialchars($rarity) ?>.
                </div>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>
</main>


<div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-card-header-style">
                <h5><i class='bx bx-award'></i> <h5 class="modal-title" id="cardModalLabel"></h5>
            </div>
            <div class="modal-body modal-card-style d-flex">
                <div class="card-body modal-card-body-content">
                    <p class="description"></p>
                    <ul class="user-details modal-card-user-details">
                        <li><i class='bx bx-user'></i> <span class="modal-user-name"></span></li>
                        <li><i class='bx bx-calendar'></i> <?= date('M'.'D'.'Y') ?></li>
                        <li><i class='bx bx-label'></i> Web Dev</li>
                        <li><i class='bx bx-envelope'></i> <span class="modal-user-email"></span></li>
                        <li class="modal-expiration-date-item" style="display: none;"><i class='bx bx-time'></i> Expires on: <span class="modal-expiration-date"></span></li>
                    </ul>
                    <div class="tech-icons modal-card-tech-icons">
                        <i class="bx bxl-html5"></i>
                        <i class="bx bxl-css3"></i>
                        <i class="bx bxl-javascript"></i>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="modal-footer">
                <h3>Take a Screen Shot</h3>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
$(document).ready(function() {
    // Sidebar toggle
    const menuIcon = document.querySelector('.menu-icon');
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (menuIcon && sidebar && backdrop) {
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
    }

    // Right nav dropdown
    const dropdownToggle = document.querySelector('.dropdown-toggle-icon');
    const mobileDropdown = document.querySelector('.right-dropdown');

    if (dropdownToggle && mobileDropdown) {
        dropdownToggle.addEventListener('click', () => {
            mobileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!dropdownToggle.contains(e.target) && !mobileDropdown.contains(e.target)) {
                mobileDropdown.classList.remove('show');
            }
        });
    }

    // Filter categories
    function filterCategories() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const categories = document.querySelectorAll('.fluid-tile');

        categories.forEach(category => {
            const title = category.querySelector('.tile-title').textContent.toLowerCase();
            if (title.includes(filter)) {
                category.style.display = "block";
            } else {
                category.style.display = "none";
            }
        });
    }
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterCategories);
    }

    // Modal functionality for viewing cards
    $('.view-save-btn').on('click', function() {
        var cardName = $(this).data('card-name');
        var cardDescription = $(this).data('card-description');
        var userName = $(this).data('user-name');
        var userEmail = $(this).data('user-email');
        var cardColor = $(this).data('card-color');
        var expirationDate = $(this).data('expiration-date'); // Get the expiration date

        // Update the modal header
        $('.modal-header.modal-card-header-style').css('background-color', cardColor);
        $('.modal-header.modal-card-header-style h5.modal-title').text(cardName);

        // Update the modal body
        $('.modal-body.modal-card-style .description').text(cardDescription);
        $('.modal-body.modal-card-style .user-details .modal-user-name').text(userName);
        $('.modal-body.modal-card-style .user-details li:nth-child(4) .modal-user-email').text(userEmail);

        // Handle the expiration date display
        var expirationDateItem = $('.modal-expiration-date-item');
        var expirationDateSpan = $('.modal-expiration-date');

        if (expirationDate) {
            expirationDateSpan.text(expirationDate);
            expirationDateItem.show();
        } else {
            expirationDateItem.hide();
            expirationDateSpan.text(''); // Clear any previous value
        }

        $('.modal-body.modal-card-style .modal-card-header-inner').css('background-color', cardColor);
        $('.modal-body.modal-card-style .modal-card-name').text(cardName);

        // Show the modal
        $('#cardModal').modal('show');
    });
});
</script>




</body>
</html>

<?php $conn = null; ?>