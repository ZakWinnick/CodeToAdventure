<?php
session_start(); // Start the session to check if the user is logged in

// If no user is logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page
    exit();  // Ensure no further code is executed
}

include '../config.php';  // Adjust the path as necessary

// Fetch all referrals from the database
$sql = "SELECT * FROM codes";
$result = $conn->query($sql);

// Query to get the total number of submissions
$totalSql = "SELECT COUNT(*) as total FROM codes";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalSubmissions = $totalRow['total']; // Store the total count
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
            box-sizing: border-box; /* Ensure padding and border are included in width and height */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); /* Optional: Add a shadow effect */
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
            color: #E7E7E5; /* Light color for the text */
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #046896; /* Blue on hover */
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
            margin-bottom: 20px; /* Adds space above the table */
            color: #E7E7E5;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1A1A1A;
        }

        td a {
            color: #E7E7E5; /* Light color for the links */
            text-decoration: none;
        }

        td a:hover {
            color: #046896; /* Blue color on hover */
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
            
            table {
                margin-top: 10px;
            }

            th, td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <header>Admin Dashboard</header>
    
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="main-content">
        <div class="total-submissions">
            <strong>Total Submissions: <?php echo $totalSubmissions; ?></strong>
        </div>
        <h2>Referral Codes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Referral Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                        <td>
                            <a href="edit_referral.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_referral.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
    </footer>
</body>
</html>
