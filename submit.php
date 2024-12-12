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
            padding: 1rem;
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

        .form-container {
            max-width: 400px;
            width: 100%;
            background-color: #123A13;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin-top: 2rem;
        }

        h1 {
            margin-bottom: 1.5rem;
            color: #DEB526;
            font-size: 1.75rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            text-align: left;
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
            width: 100%;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: #142a13;
            background-color: #87b485;
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
            margin-top: 1rem;
            display: inline-block;
            background-color: #87b485;
            color: #142a13;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-link:hover {
            background-color: #6f946f;
        }

        @media (max-width: 768px) {
            .title-bar {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .menu {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .form-container {
                padding: 1.5rem;
                margin-top: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            button, .back-link {
                font-size: 1rem;
                padding: 0.5rem;
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

    <div class="form-container">
        <h1>Submit Your Referral Code</h1>

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
</body>
</html>
