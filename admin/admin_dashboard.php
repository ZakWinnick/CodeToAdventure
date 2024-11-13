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
$totalQuery = "SELECT COUNT(*) AS total_submissions FROM codes";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalSubmissions = $totalRow['total_submissions'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Code to Adventure</title>
    <style>
        /* General styles */
        body {
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #046896;
            padding: 20px;
            text-align: center;
            color: #E7E7E5;
            font-size: 40px;
        }

        nav {
            background-color: #B4232A;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #046896;
        }

        .main-content {
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #1A1A1A;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .total-submissions {
            font-size: 24px;
            color: #00acee;
            text-align: right;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #046896;
            color: #E7E7E5;
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            text-align: center;
        }

        footer a {
            color: #00acee;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile-friendly adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 10px;
                width: 100%;
                box-sizing: border-box;
            }

            .container {
                padding: 20px;
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            .total-submissions {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<header>Code to Adventure - Admin Dashboard</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="main-content">
    <div class="container">
        <!-- Display the total number of submissions at the top right -->
        <div class="total-submissions">
            <strong>Total Submissions: </strong><?php echo $totalSubmissions; ?>
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
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>

<?php
$conn->close();
?>
