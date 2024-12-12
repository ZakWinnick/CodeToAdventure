<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify username and password
    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Set persistent login cookie if "Remember Me" is checked
        if (!empty($_POST['remember'])) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (30 * 24 * 60 * 60); // 30 days
            setcookie('login_token', $token, $expires, '/');

            // Store the token in the database
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET token='$hashedToken', token_expires=DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE username='$username'");
        }

        header('Location: admin.php');
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #142a13;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: #123A13;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
            color: #DEB526;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #87b485;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #87b485;
            border-radius: 5px;
            background-color: #1a3e2b;
            color: #E7E7E5;
        }

        .form-group input:focus {
            border-color: #6f946f;
            outline: none;
        }

        .form-group .checkbox {
            display: flex;
            align-items: center;
        }

        .form-group .checkbox input {
            margin-right: 0.5rem;
        }

        .form-group .checkbox label {
            font-size: 0.9rem;
        }

        .login-button {
            width: 100%;
            background-color: #87b485;
            color: #142a13;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #6f946f;
        }

        .error-message {
            color: #f44336;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-container h1 {
                font-size: 1.5rem;
            }

            .form-group input {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .login-button {
                font-size: 1rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group checkbox">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
