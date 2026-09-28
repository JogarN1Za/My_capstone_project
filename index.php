<?php
session_start();
include ('conn.php'); // Database connection

// 🔒 Common function for redirecting with message
function redirect_with_message($message, $location = 'users.php') {
    echo "<script>alert('$message');</script>";
    echo "<script>window.location.href='$location';</script>";
    exit();
}

// 🔒 Handle User Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registerUser'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $password = trim($_POST['password']);
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);

    // ✅ Handling file upload
    $profile_picture_name = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture'];
        $profile_picture_name = time() . '_' . basename($profile_picture['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $profile_picture_name;

        if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
            redirect_with_message('❌ Profile Picture Upload Failed!');
        }
    }

    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($contact_number) || empty($password) || empty($year) || empty($section)) {
        redirect_with_message('⚠️ All fields are required!');
    }

    try {
        // Check if username or email exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_tb WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            redirect_with_message('⚠️ Username or Email already exists!');
        }

        // ✅ Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO admin_tb (first_name, last_name, username, email, contact_number, password, year, section, profile_picture, is_online) 
                                VALUES (:first_name, :last_name, :username, :email, :contact_number, :password, :year, :section, :profile_picture, 0)");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':username' => $username,
            ':email' => $email,
            ':contact_number' => $contact_number,
            ':password' => $hashed_password,
            ':year' => $year,
            ':section' => $section,
            ':profile_picture' => $profile_picture_name
        ]);

        redirect_with_message('✅ User registered successfully!');
    } catch (PDOException $e) {
        redirect_with_message('❌ Database Error: ' . $e->getMessage());
    }
}

// 🔒 Handle User Login (index.php)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginUser'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        redirect_with_message('⚠️ Username and Password are required!', 'index.php');
    }

    try {
        // ✅ Check if username exists
        $stmt = $conn->prepare("SELECT * FROM admin_tb WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['tbl_user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            $updateStmt = $conn->prepare("UPDATE admin_tb SET is_online = 1 WHERE tbl_user_id = :user_id");
            $updateStmt->execute([':user_id' => $user['tbl_user_id']]);

            redirect_with_message('✅ Login successful!', 'home.php');
        } else {
            redirect_with_message('❌ Invalid username or password!', 'index.php');
        }
    } catch (PDOException $e) {
        redirect_with_message('❌ Database Error: ' . $e->getMessage(), 'index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Login Page</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        height: 100vh;
        background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: auto; /* Enable scrolling if content overflows */
    }

    .container {
        display: flex;
        width: 900px;
        height: 500px;
        border: 2px solid #8a2be2;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(138,43,226,0.2), rgba(0,0,50,0.3));
        backdrop-filter: blur(10px);
        margin: 20px; /* Add some margin around the container */
    }

    .left-panel, .right-panel {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        overflow: hidden; /* Hide overflowing text */
    }

    .left-panel h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .left-panel h2 {
        font-weight: normal;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    label {
        font-weight: bold;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 10px 15px;
        border-radius: 8px;
        border: none;
        font-size: 1rem;
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: white;
    }

    .login-btn {
        background: linear-gradient(to right, #a066f5, #50dfff);
        color: white;
        border: none;
        padding: 12px;
        font-size: 1.2rem;
        font-weight: bold;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        width: 100%;
    }

    .login-btn:hover {
        transform: translateY(-2px);
    }

    .right-panel {
        border-left: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; /* To contain the typing text */
    }

    .typing-text {
        font-family: monospace;
        font-size: 1.6rem; /* Slightly larger font for greeting */
        white-space: nowrap;
        overflow: hidden;
        border-right: .15em solid white; /* Cursor effect */
    }
    .bx {
        color: #111827; /* Change 'yellow' to your desired color */
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
        .container {
            flex-direction: column; /* Stack panels vertically on smaller screens */
            width: 90%; /* Make container take up more width */
            height: auto; /* Adjust height automatically */
        }

        .left-panel, .right-panel {
            padding: 30px;
            text-align: center; /* Center text in panels */
        }

        .right-panel {
            border-left: none; /* Remove border between panels */
            border-top: 1px solid rgba(255,255,255,0.1); /* Add a top border */
        }

        .left-panel h1 {
            font-size: 2rem;
        }

        .left-panel h2 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .typing-text {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 480px) {
        .left-panel, .right-panel {
            padding: 20px;
        }

        .left-panel h1 {
            font-size: 1.7rem;
        }

        .left-panel h2 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .typing-text {
            font-size: 1.2rem;
        }
    }
</style>

</head>
<body>
<div class="container">
    <div class="left-panel">
        <h1>Welcome to!</h1>
            <h2>Web. Dev.</h2>
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" />
                </div>
                <div class="form-group password-wrapper">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" />
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class='bx bx-low-vision'></i>
                    </button>
                </div>
                <button type="submit" class="login-btn" name="loginUser">Login</button>
            </form>
        </div>
        <div class="right-panel">
            <div class="typing-text" id="typingGreeting"></div>
        </div>
    </div>
</div>

    <script>
    function togglePassword() {
        const passField = document.getElementById('password');
        const toggleIcon = document.querySelector(".toggle-password i");

        if (passField.type === 'password') {
            passField.type = 'text';
            if (toggleIcon) {
                toggleIcon.classList.remove('bx-low-vision');
                toggleIcon.classList.add('bx-show'); // Ensure you have the 'bx-show' icon class if desired
            }
        } else {
            passField.type = 'password';
            if (toggleIcon) {
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-low-vision');
            }
        }
    }

    const greetingElement = document.getElementById('typingGreeting');
    const greetings = ["Hello!", "Welcome!", "Mabuhay!", "Greetings!", "Bienvenido!"]; // Array of greetings
    let greetingIndex = 0;
    let charIndex = 0;
    let typingSpeed = 100; // Adjust typing speed (milliseconds per character)
    let pauseDuration = 1500; // Pause duration after each greeting (milliseconds)

    function typeGreeting() {
        if (greetingIndex < greetings.length) {
            const currentGreeting = greetings[greetingIndex];

            if (charIndex < currentGreeting.length) {
                greetingElement.textContent = currentGreeting.substring(0, charIndex + 1);
                charIndex++;
                setTimeout(typeGreeting, typingSpeed);
            } else {
                // Pause after the greeting is typed
                setTimeout(() => {
                    charIndex = 0;
                    greetingIndex++;
                    if (greetingIndex >= greetings.length) {
                        greetingIndex = 0; // Loop back to the first greeting
                    }
                    greetingElement.textContent = ""; // Clear the text
                    typeGreeting(); // Start typing the next greeting
                }, pauseDuration);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', typeGreeting);
</script>

</body>
</html>
