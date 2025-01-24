<?php
session_start();
include 'config.php';

// Remove session
session_unset();
session_destroy();

// Clear token in database
if (isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];
    $stmt = $conn->prepare("UPDATE users SET login_token = NULL, token_expires = NULL WHERE login_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    setcookie('login_token', '', time() - 3600, '/'); // Expire cookie
}

// Redirect to home page
header('Location: ../index.php');
exit;
?>
