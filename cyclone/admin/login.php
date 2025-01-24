<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            $token = bin2hex(random_bytes(64));
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin login panel for Code to Adventure.">
    <title>Login - Admin Panel</title>

    <!-- Preload key resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --dark-green: #132A13;
            --hunter-green: #31572C;
            --fern-green: #4F772D;
            --moss-green: #90A955;
            --mindaro: #ECF39E;
            --text-white: #FFFFFF;
            --border-radius: 8px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--dark-green);
            color: var(--text-white);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            width: 90%;
            background-color: var(--hunter-green);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            color: var(--mindaro);
            font-size: 1.75rem;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--mindaro);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid var(--fern-green);
            border-radius: var(--border-radius);
            background-color: var(--dark-green);
            color: var(--text-white);
        }

        .form-group input:focus {
            border-color: var(--moss-green);
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
            color: var(--text-white);
        }

        .login-button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--dark-green);
            background-color: var(--mindaro);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color var(--transition-speed) ease;
        }

        .login-button:hover {
            background-color: var(--moss-green);
        }

        .error-message {
            color: #f44336;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        @media (max-height: 500px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-button {
                font-size: 1rem;
                padding: 0.5rem;
            }

            .form-group input {
                padding: 0.5rem;
                font-size: 0.9rem;
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
