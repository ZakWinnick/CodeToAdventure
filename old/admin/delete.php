<?php
session_start();
include 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM codes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the admin panel with a success message
        header("Location: admin.php?message=Deleted successfully");
        exit;
    } else {
        die("Failed to delete submission. Please try again.");
    }
} else {
    die("Invalid ID.");
}
?>
