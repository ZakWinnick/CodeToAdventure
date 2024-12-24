<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "Step 1: Starting<br>"; // Debug point 1

session_start();
echo "Step 2: Session started<br>"; // Debug point 2

include 'config.php';
echo "Step 3: Config included<br>"; // Debug point 3

// Basic query to test database connection
$test_query = $conn->query("SELECT COUNT(*) as count FROM codes");
if ($test_query) {
    $count = $test_query->fetch_assoc()['count'];
    echo "Step 4: Database connected, found {$count} codes<br>"; // Debug point 4
} else {
    echo "Step 4: Database error: " . $conn->error . "<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Debug</title>
    <style>
        body {
            background-color: #142a13;
            color: #E7E7E5;
            font-family: sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>
    <h1>Debug Test Page</h1>
    <p>If you can see this, HTML is rendering correctly.</p>
</body>
</html>