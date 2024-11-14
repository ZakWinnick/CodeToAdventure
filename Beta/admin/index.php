<?php
session_start();

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}

// You can optionally redirect to the admin dashboard or another page if logged in
header("Location: admin_dashboard.php");
exit();
?>
