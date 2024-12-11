<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Code to Adventure</title>
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
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            padding: 1rem 1.5rem;
            background-color: #123A13;
            color: #E7E7E5;
        }

        .title-bar h1 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #DEB526;
        }

        .title-bar button {
            background-color: #87b485;
            color: #142a13;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-size: 1.25rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .title-bar button:hover {
            background-color: #6f946f;
        }

        .menu-bar {
            width: 100%;
            max-width: 1200px;
            background-color: #1a3e2b;
            padding: 0.5rem 1.5rem;
            margin: 0 auto;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        .menu a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.5rem 1rem;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #6f946f;
            color: #142a13;
        }

        .content {
            max-width: 400px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #1a3e2b;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #87b485;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        input {
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }

        button {
            background-color: #87b485;
            color: #142a13;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #6f946f;
        }

        .error {
            background-color: #ff4d4d;
            color: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            border-radius: 5px;
        }

        .footer-link {
            text-align: center;
            margin-top: 1rem;
            color: #87b485;
        }

        .footer-link a {
            color: #87b485;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #1a3e2b;
            color: #E7E7E5;
            width: 100%;
            margin-top: auto;
        }

        footer a {
            color: #87b485;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .menu {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .menu a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            .content {
                padding: 1rem;
            }

            input {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="title-bar">
        <h1>Code to Adventure</h1>
        <button onclick="window.location.href='submit.php';">Submit Code</button>
    </div>

    <div class="menu-bar">
        <nav class="menu">
            <a href="login.php">Login</a>
            <a href="../index.php">Home</a>
        </nav>
    </div>

    <div class="content">
        <h2>Admin Registration</h2>

        <?php if (isset($error_message)): ?>
            <div class="error"> <?php echo htmlspecialchars($error_message); ?> </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>

        <div class="footer-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <footer>
        Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a>
    </footer>
</body>
</html>
