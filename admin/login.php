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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --background: #ffffff;
            --surface: #f8fafc;
            --surface-hover: #f1f5f9;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --radius-sm: 8px;
            --error: #ef4444;
        }

        [data-theme="dark"] {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #34d399;
            --accent: #fbbf24;
            --background: #0f172a;
            --surface: #1e293b;
            --surface-hover: #334155;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --shadow: rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--background), var(--surface));
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .login-container {
            max-width: 420px;
            width: 90%;
            background: var(--surface);
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: 0 20px 25px -5px var(--shadow), 0 10px 10px -5px var(--shadow);
            border: 1px solid var(--border);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .logo-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            color: var(--text);
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--background);
            color: var(--text);
            transition: all 0.2s ease;
        }

        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 0.9rem;
            color: var(--text-muted);
            cursor: pointer;
            margin: 0;
        }

        .login-button {
            width: 100%;
            padding: 0.875rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: var(--primary);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .login-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px var(--shadow);
        }

        .error-message {
            color: var(--error);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: var(--radius-sm);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .theme-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--surface);
            border: 1px solid var(--border);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: var(--text);
        }

        .theme-toggle:hover {
            background: var(--surface-hover);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
        <span id="theme-icon">🌙</span>
    </button>

    <div class="login-container">
        <div class="logo-section">
            <div class="logo-text">Code to Adventure</div>
            <div class="logo-subtitle">Admin Panel</div>
        </div>

        <h1>Sign In</h1>

        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>
            <button type="submit" class="login-button">Sign In</button>
        </form>
    </div>

    <script>
        function initTheme() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            document.documentElement.setAttribute('data-theme', theme);
            updateThemeIcon(theme);
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcon(next);
        }

        function updateThemeIcon(theme) {
            document.getElementById('theme-icon').textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        initTheme();
    </script>
</body>
</html>
