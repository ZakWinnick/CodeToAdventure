<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Debugging
echo "Connected to the database.<br>";

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
        /* Start with a minimal version of CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: #ffffff;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel - Submissions</h1>
        <p>Total Submissions: <?php echo $totalCount; ?></p>
    </div>
</body>
</html>
