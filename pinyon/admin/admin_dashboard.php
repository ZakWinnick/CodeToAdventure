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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #142a13;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            padding: 1rem 1.5rem;
            background-color: #123A13;
            color: #E7E7E5;
        }

        .title-bar h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #DEB526;
        }

        .title-bar button {
            background-color: #87b485;
            color: #142a13;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-size: 1.25rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .title-bar button:hover {
            background-color: #6f946f;
        }

        .menu-bar {
            width: 100%;
            max-width: 1200px;
            background-color: #1a3e2b;
            padding: 0.5rem 1.5rem;
            margin: 0 auto;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        .menu a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.5rem 1rem;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #6f946f;
            color: #142a13;
        }

        .content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .total-box {
            display: flex;
            justify-content: space-around;
            background-color: #1a3e2b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .total-box div {
            text-align: center;
        }

        .total-box .total-heading {
            font-size: 1.5rem;
            font-weight: bold;
            color: #87b485;
        }

        .table-container {
            background-color: #1a3e2b;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th, table td {
            text-align: left;
            padding: 0.5rem;
            border: 1px solid #333;
        }

        table th {
            background-color: #123A13;
            color: #E7E7E5;
        }

        table tr:nth-child(even) {
            background-color: #1a3e2b;
        }

        table tr:hover {
            background-color: #333;
        }

        .back-to-top {
            display: inline-block;
            margin-top: 1rem;
            background-color: #87b485;
            color: #142a13;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .back-to-top:hover {
            background-color: #6f946f;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #1a3e2b;
            color: #E7E7E5;
            width: 100%;
            margin-top: auto;
        }

        footer a {
            color: #87b485;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .menu {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .menu a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .total-box {
                flex-direction: column;
                gap: 1rem;
            }

            .content {
                padding: 1rem;
            }

            table th, table td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="title-bar">
        <h1>Code to Adventure</h1>
        <button onclick="window.location.href='submit.php';">Submit Code</button>
    </div>

    <div class="menu-bar">
        <nav class="menu">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="../index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="content">
        <div class="total-box">
            <div>
                <div class="total-heading">Latest Submission</div>
                <div>
                    <strong><?php echo htmlspecialchars($latestSubmission['name']); ?></strong> 
                    (<a href="https://x.com/<?php echo htmlspecialchars($latestSubmission['username']); ?>" target="_blank" style="color: #87b485;">@<?php echo htmlspecialchars($latestSubmission['username']); ?></a>) - 
                    <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($latestSubmission['referral_code']); ?>" target="_blank" style="color: #87b485;">Use this Referral Code</a>
                </div>
            </div>
            <div>
                <div class="total-heading">Total Submissions</div>
                <div><?php echo $totalSubmissions; ?></div>
            </div>
        </div>

        <div class="table-container">
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
        </div>

        <a href="#" class="back-to-top" onclick="window.scrollTo(0, 0);">Back to Top</a>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a>
    </footer>
</body>
</html>
