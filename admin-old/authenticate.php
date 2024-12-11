<?php
session_start();
include '../config.php'; // Database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Prepare query to fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify (which compares hashed password)
        if (password_verify($password, $user['password'])) {
            // Store session information and redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            exit();
        } else {
            // Incorrect password
            header('Location: login.php?error=Incorrect password');
            exit();
        }
    } else {
        // User not found
        header('Location: login.php?error=User not found');
        exit();
    }
}

$conn->close();
?>
