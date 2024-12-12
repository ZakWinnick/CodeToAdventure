<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Ensure no output is sent before headers
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
if (!$countResult) {
    die("Count query failed: " . $conn->error);
}
$countData = $countResult->fetch_assoc();
$totalCount = $countData['total'];

$latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
if (!$latestResult) {
    die("Latest query failed: " . $conn->error);
}
$latestSubmission = $latestResult->fetch_assoc();

$allSubmissions = $conn->query("SELECT * FROM codes ORDER BY id ASC");
if (!$allSubmissions) {
    die("All submissions query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Submissions</title>
    <style>
        /* Styles */
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <div class="header">
            <h1>Admin Panel - Submissions</h1>
        </div>

        <div class="summary">
            <div>
                <h2>Total Submissions</h2>
                <p><?php echo $totalCount; ?> codes submitted</p>
            </div>
            <div>
                <h2>Latest Submission</h2>
                <?php if ($latestSubmission): ?>
                    <p>Code: <?php echo $latestSubmission['referral_code']; ?></p>
                    <p>Submitted by: <?php echo $latestSubmission['name']; ?></p>
                <?php else: ?>
                    <p>No submissions yet</p>
                <?php endif; ?>
            </div>
        </div>

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
                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['referral_code']; ?></td>
                        <td>
                            <div class="actions">
                                <button class="edit" onclick="window.location.href='edit.php?id=<?php echo $row['id']; ?>';">Edit</button>
                                <button class="delete" onclick="if(confirm('Are you sure you want to delete this code?')) window.location.href='delete.php?id=<?php echo $row['id']; ?>';">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
