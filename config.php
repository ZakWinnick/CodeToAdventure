<?php
// Database configuration
$servername = "mysql.randomrivianreferral.co"; // Change if necessary
$username = "rrr_db"; // Your MySQL username
$password = "Fr@km00v"; // Your MySQL password
$dbname = "rrr_referrals"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
