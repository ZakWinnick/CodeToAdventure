<?php
session_start(); // Start the session to check if the user is logged in

// If no user is logged in or not an admin, redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page
    exit();  // Ensure no further code is executed
}

include '../config.php';  // Adjust the path as necessary

// Query to get the total number of users
$totalSqlUsers = "SELECT COUNT(*) as total FROM users";
$totalResultUsers = $conn->query($totalSqlUsers);
$totalRowUsers = $totalResultUsers->fetch_assoc();
$totalUsers = $totalRowUsers['total']; // Store the total user count

// Query to get the total number of submissions
$totalSqlSubmissions = "SELECT COUNT(*) as total FROM codes";
$totalResultSubmissions = $conn->query($totalSqlSubmissions);
$totalRowSubmissions = $totalResultSubmissions->fetch_assoc();
$totalSubmissions = $totalRowSubmissions['total']; // Store the total submission count
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Code to Adventure</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 100vh; /* Ensure body takes full viewport height */
            text-align: center;
        }

        header {
            background-color: #046896; /* Header blue */
            color: #E7E7E5;
            padding: 20px;
            text-align: center;
            font-size: 36px;
            width: 100%;  /* Ensure header stretches across full width */
        }

        nav {
            background-color: #B4232A; /* Red for the navigation bar */
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            width: 100%;
        }

        nav a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #046896;
        }

        .main-content {
            flex: 1;
            padding: 40px 20px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        .total-submissions {
            font-size: 18px;
            text-align: right;
            margin-bottom: 20px;
            color: #E7E7E5;
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            width: 100%;
            text-align: center;
            font-size: 16px;
        }

        footer a {
            color: #E7E7E5;
            text-decoration: none;
        }

        footer a:visited {
            color: #B4232A; /* Red for visited links */
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }

            .main-content {
                padding: 30px 10px;
            }
        }
    </style>
</head>
<body>
    <header>Admin Dashboard</header>
    
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="users.php">Manage Users</a> <!-- Link to Users Management Page -->
        <a href="submissions.php">Manage Submissions</a> <!-- Link to Submissions Management Page -->
        <a href="logout.php">Logout</a>
    </nav>

    <div class="main-content">
        <!-- Display Total Users Count -->
        <div class="total-submissions">
            <strong>Total Users: <?php echo $totalUsers; ?></strong>
        </div>

        <!-- Display Total Submissions Count -->
        <div class="total-submissions">
            <strong>Total Submissions: <?php echo $totalSubmissions; ?></strong>
        </div>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
    </footer>
</body>
</html>
