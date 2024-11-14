<?php
session_start(); // Start the session to check if the user is logged in

// If no user is logged in or not an admin, redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page
    exit();  // Ensure no further code is executed
}

include '../config.php';  // Adjust the path as necessary

// Fetch all submissions from the database
$sql = "SELECT * FROM codes";
$result = $conn->query($sql);

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
    <title>Manage Submissions - Admin Dashboard</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <header>Admin Dashboard - Manage Submissions</header>
    
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="main-content">
        <div class="total-submissions">
            <strong>Total Submissions: <?php echo $totalSubmissions; ?></strong>
        </div>

        <h3>Add New Submission</h3>
        <!-- Form to add submissions (similar to user form) -->
        <form action="add_submission.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="referralCode">Referral Code:</label>
            <input type="text" id="referralCode" name="referralCode" required>
            <br>
            <button type="submit">Add Submission</button>
        </form>

        <h3>Existing Submissions</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Referral Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                        <td>
                            <a href="edit_submission.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_submission.php?id=<?php echo $row['id']; ?>">Delete</a>
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
