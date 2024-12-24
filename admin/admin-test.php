<?php
// First, enable error reporting at the very top
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start the session
session_start();

// Try to include config and catch any errors
try {
    require 'config.php';
    echo "Successfully connected to config<br>";
} catch (Exception $e) {
    echo "Error with config: " . $e->getMessage() . "<br>";
}

// Test database connection
try {
    if (isset($conn)) {
        $test_query = $conn->query("SELECT COUNT(*) as count FROM codes");
        if ($test_query) {
            $count = $test_query->fetch_assoc()['count'];
            echo "Database connected, found {$count} codes<br>";
        } else {
            echo "Database error: " . $conn->error . "<br>";
        }
    } else {
        echo "No database connection found<br>";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Test</title>
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
    <h1>Admin Test Page</h1>
    <p>If you can see this, basic HTML is working.</p>
</body>
</html>