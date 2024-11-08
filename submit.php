<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Referral Code - Code to Adventure</title>
    <style>
        * {
            box-sizing: border-box; /* Ensure padding/margins don't exceed element size */
        }

        body {
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        header {
            background-color: #046896;
            padding: 20px;
            text-align: center;
            color: #E7E7E5;
            font-size: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        nav {
            background-color: #B4232A;
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #046896;
        }

        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            padding: 40px 20px;
            margin: 0 auto;
            text-align: center;
            box-sizing: border-box;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #1A1A1A;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            font-size: 16px;
            color: #E7E7E5;
        }

        input, button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        input {
            background-color: #333;
            color: white;
        }

        button {
            background-color: #00acee;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #007bb5;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            color: #00acee;
            margin-top: 20px;
            text-decoration: none;
            border: 2px solid #00acee;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-link:hover {
            background-color: #00acee;
            color: #000;
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        footer a {
            color: #00acee;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile-friendly adjustments */
        @media (max-width: 768px) {
            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }

            .main-content {
                padding: 30px 10px;
                width: 100%;
                box-sizing: border-box;
            }

            .container {
                padding: 20px;
                width: 100%;
            }

            form {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<header>Code to Adventure</header>

<nav>
    <a href="index.php">Home</a>
    <a href="submit.php">Submit Code</a>
    <a href="api-docs.html">API Docs</a>
    <a href="changelog.html">Changelog</a>
</nav>

<div class="main-content">
    <div class="container">
        <!-- Display error message if there's a duplicate -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
            <div class="error">Duplicate referral code found! Please use a different code.</div>
        <?php endif; ?>

        <!-- Display success message if submission was successful -->
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="success">Your referral code has been submitted successfully!</div>
        <?php endif; ?>

        <form action="store_code.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="username">X Username (without @ or 'None')</label>
            <input type="text" id="username" name="username">

            <label for="referralCode">Referral Code (Just the code - No URL)</label>
            <input type="text" id="referralCode" name="referralCode" required>

            <button type="submit">Submit</button>
        </form>

        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="https://zak.codetoadventure.com" target="_blank">Zak's Referral Code</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>
