<?php
// Diagnostic script to help troubleshoot white screen
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<!DOCTYPE html><html><head><title>Diagnostic</title></head><body>";
echo "<h1>Diagnostic Report</h1>";
echo "<pre>";

// PHP Version
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n\n";

// Memory
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "s\n\n";

// Extensions
echo "MySQLi Extension: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n\n";

// Test config.php
echo "Testing config.php...\n";
if (file_exists('config.php')) {
    echo "✓ config.php exists\n";
    try {
        require_once 'config.php';
        echo "✓ config.php loaded successfully\n";

        if (isset($conn)) {
            echo "✓ \$conn variable exists\n";

            if ($conn->connect_error) {
                echo "✗ Database connection error: " . $conn->connect_error . "\n";
            } else {
                echo "✓ Database connected successfully\n";

                // Test query
                $result = $conn->query("SELECT COUNT(*) as count FROM codes");
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo "✓ Codes table accessible - " . $row['count'] . " codes found\n";
                } else {
                    echo "✗ Query failed: " . $conn->error . "\n";
                }
            }
        } else {
            echo "✗ \$conn variable not set in config.php\n";
        }
    } catch (Exception $e) {
        echo "✗ Error loading config.php: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ config.php not found\n";
}

echo "\n--- File Checks ---\n";
echo "logo.png exists: " . (file_exists('logo.png') ? 'YES (' . filesize('logo.png') . ' bytes)' : 'NO') . "\n";
echo "logo-dark.png exists: " . (file_exists('logo-dark.png') ? 'YES (' . filesize('logo-dark.png') . ' bytes)' : 'NO') . "\n";

echo "</pre></body></html>";
?>
