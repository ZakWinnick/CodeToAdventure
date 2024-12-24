<?php
// Start with error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start session first, before any output
session_start();

// Include configuration
include 'config.php';

// Initialize variables
$debug_messages = [];

try {
    // Test database connection
    $test_query = $conn->query("SELECT COUNT(*) as count FROM codes");
    if ($test_query) {
        $count = $test_query->fetch_assoc()['count'];
        $debug_messages[] = "Database connected, found {$count} codes";
    } else {
        $debug_messages[] = "Database error: " . $conn->error;
    }
} catch (Exception $e) {
    $debug_messages[] = "Error: " . $e->getMessage();
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
        .debug-message {
            background-color: #1a3e2b;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Debug Test Page</h1>
    <?php foreach ($debug_messages as $message): ?>
        <div class="debug-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endforeach; ?>
</body>
</html>