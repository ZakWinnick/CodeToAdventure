<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../config.php';

// Get the referral ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query to delete the referral
    $stmt = $conn->prepare("DELETE FROM codes WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the query and check for success
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php"); // Redirect to the dashboard after success
        exit();
    } else {
        echo "Error deleting referral: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No referral ID provided!";
    exit();
}

$conn->close();
?>
