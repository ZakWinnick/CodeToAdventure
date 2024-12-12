<?php
session_start();

// Clear the session data
session_unset();
session_destroy();

// Remove the persistent login cookie
if (isset($_COOKIE['login_token'])) {
    setcookie('login_token', '', time() - 3600, '/'); // Set cookie to expire in the past
}

// Redirect to the home page
header('Location: ../index.php');
exit;
