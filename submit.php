<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Referral Code - Code to Adventure</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #1a3e2b;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #87b485;
            font-size: 2rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-size: 1rem;
            color: #E7E7E5;
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

        .message {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #ff4d4d;
            color: #fff;
        }

        .success {
            background-color: #4caf50;
            color: #fff;
        }

        .back-link {
            display: inline-block;
            color: #87b485;
            margin-top: 20px;
            text-decoration: none;
            border: 2px solid #87b485;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-link:hover {
            background-color: #87b485;
            color: #142a13;
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

            .form-container {
                padding: 15px;
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
            <a href="index.php">Home</a>
            <a href="submit.php">Submit Code</a>
            <a href="api-docs.html">API Docs</a>
            <a href="changelog.html">Changelog</a>
        </nav>
    </div>

    <div class="content">
        <div class="form-container">
            <!-- Display error message if there's a duplicate -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
                <div class="message error">Duplicate referral code found! Please use a different code.</div>
            <?php endif; ?>

            <!-- Display success message if submission was successful -->
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                <div class="message success">Your referral code has been submitted successfully!</div>
            <?php endif; ?>

            <form action="store_code.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="referralCode">Referral Code (Just the code - No URL)</label>
                <input type="text" id="referralCode" name="referralCode" required>

                <button type="submit">Submit</button>
            </form>

            <a href="index.php" class="back-link">Back to Home</a>
        </div>
    </div>

    <footer>
        Created by <a href="https://zakwinnick.com" target="_blank">Zak Winnick</a> | <a href="https://zak.codetoadventure.com" target="_blank">Zak's Referral Code</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a>
    </footer>
</body>
</html>
