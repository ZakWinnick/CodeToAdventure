<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify username and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Persistent login: Generate and store a token if "Remember Me" is checked
        if (!empty($_POST['remember'])) {
            $token = bin2hex(random_bytes(64)); // Generate a 128-character token
            $expires = time() + (30 * 24 * 60 * 60); // 30 days
            setcookie('login_token', $token, $expires, '/', '', false, true);

            // Store the token in the database
            $stmt = $conn->prepare("UPDATE users SET login_token = ?, token_expires = ? WHERE username = ?");
            $expiresDate = date('Y-m-d H:i:s', $expires);
            $stmt->bind_param("sss", $token, $expiresDate, $username);
            $stmt->execute();
        }

        header('Location: admin.php');
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}
?>
