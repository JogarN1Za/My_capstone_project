<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Front End Web Dev | Tutorials</title>
    <!-- CSS -->
    <link rel="stylesheet" href="style.css" />
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<style>

/* ✅ Ensure body takes full height */
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  margin: 0;
}

/* ✅ Make main content grow to push footer down */
.main {
  flex-grow: 1;
  padding-bottom: 50px; /* Space for footer */
}

/* ✅ Sidebar should not overlap content */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh; /* Full height */
  width: 250px; /* Adjust width */
  background: #f8f9fa; /* Light background */
  padding-top: 20px;
  z-index: 1000; /* Ensure it stays above content */
}

/* ✅ Ensure the content area does not overlap */
.content {
  margin-left: 250px; /* Same as sidebar width */
  padding: 20px;
}

/* ✅ Footer Styling */
.footer {
  background: #4d75ec;
  color: white;
  text-align: center;
  padding: 15px 0;
  font-size: 16px;
  width: 100%;
}

</style>
<body>

    <footer class="footer">
        <p>&copy; 2025 Front-End Web Development. All rights reserved.</p>
    </footer>
    
    <!-- ✅ Include JavaScript files here -->
    <script src="js/dash.js"></script>
    
    </body>
    </html>
    