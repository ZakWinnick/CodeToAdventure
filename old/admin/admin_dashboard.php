<?php
session_start(); // Start the session to check if the user is logged in

// No need for admin check; anyone logged in can access this page
// Just checking if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
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

// Query to get the latest submission
$latestSql = "SELECT * FROM codes ORDER BY id DESC LIMIT 1";
$latestResult = $conn->query($latestSql);
$latestSubmission = $latestResult->fetch_assoc();

// Fetch all submissions ordered from oldest to newest
$submissionsSql = "SELECT * FROM codes ORDER BY id ASC"; // Sorted by ID in ascending order
$submissionsResult = $conn->query($submissionsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code to Adventure - Admin Dashboard</title>
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
            min-height: 100vh;
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
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the content */
        }

        /* Style for Total Count Box */
        .total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1A1A1A;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            font-size: 24px;
            color: #E7E7E5;
            width: 100%; /* Ensure full width */
            max-width: 900px; /* Max width for large screens */
        }

        .total-box div {
            text-align: center;
        }

        .total-box .total-heading {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer-links {
            margin-top: 20px;
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

        /* Table Styles for Submissions List */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #046896;
            color: #E7E7E5;
        }

        table tr:nth-child(even) {
            background-color: #1A1A1A;
        }

        table tr:hover {
            background-color: #333;
        }

        table a {
            color: #00acee;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }

        /* Mobile Styling */
        @media (max-width: 768px) {
            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }

            .main-content {
                padding: 30px 10px;
                width: 100%;
                box-sizing: border-box;
            }

            .total-box {
                flex-direction: column;
                text-align: center;
                margin-bottom: 20px;
            }

            .total-box div {
                margin-bottom: 10px;
            }

            table {
                overflow-x: auto;
                display: block;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }
        }

        /* Back to Top Button */
        .back-to-top {
            background-color: #046896;
            color: #E7E7E5;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
        }

        .back-to-top:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <header>Code to Adventure - Admin Dashboard</header>
    
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="../index.php">Home</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="main-content">
        <!-- Display Total Users and Submissions in a prominent box -->
        <div class="total-box">
            <div>
                <div class="total-heading">Latest Submission</div>
                <div class="total-count">
                    <strong><?php echo htmlspecialchars($latestSubmission['name']); ?></strong> 
                    (<a href="https://x.com/<?php echo htmlspecialchars($latestSubmission['username']); ?>" target="_blank" style="color: #00acee;">@<?php echo htmlspecialchars($latestSubmission['username']); ?></a>) - 
                    <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($latestSubmission['referral_code']); ?>" target="_blank" style="color: #00acee;">Use this Referral Code</a>
                </div>
            </div>

            <div>
                <div class="total-heading">Total Submissions</div>
                <div class="total-count"><?php echo $totalSubmissions; ?></div>
            </div>
        </div>

        <!-- List of all submissions -->
        <h2>All Submissions</h2>
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
                <?php while ($row = $submissionsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                        <td>
                            <a href="edit_referral.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_referral.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Back to Top Button -->
        <a href="#" class="back-to-top" onclick="window.scrollTo(0, 0);">Back to Top</a>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
    </footer>
</body>
</html>
