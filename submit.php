<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Submit your Rivian referral code to Code to Adventure and share the rewards with the community.">
    <meta name="author" content="Zak Winnick">
    <title>Submit Your Referral Code - Code to Adventure</title>

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
            --button-radius: 40px;
            --max-width: 1200px;
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
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
        }

        .title-bar {
            background-color: var(--hunter-green);
            width: 100%;
            padding: 1rem 1.5rem;
            text-align: center;
            color: var(--mindaro);
        }

        .title-bar h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .menu-bar {
            background-color: var(--fern-green);
            width: 100%;
            padding: 0.75rem 1.5rem;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            max-width: var(--max-width);
            margin: 0 auto;
        }

        .menu a {
            color: var(--mindaro);
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: background-color var(--transition-speed) ease;
            border-radius: var(--border-radius);
        }

        .menu a:hover {
            background-color: var(--moss-green);
            color: var(--dark-green);
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            background-color: var(--hunter-green);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin-top: 2rem;
        }

        h1 {
            margin-bottom: 1.5rem;
            color: var(--mindaro);
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
            color: var(--text-white);
        }

        input {
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: var(--border-radius);
            background-color: #fff;
            color: #000;
        }

        button {
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

        button:hover {
            background-color: var(--moss-green);
        }

        .message {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: var(--border-radius);
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
            background-color: var(--moss-green);
            color: var(--dark-green);
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: background-color var(--transition-speed) ease, color var(--transition-speed) ease;
        }

        .back-link:hover {
            background-color: var(--mindaro);
            color: var(--dark-green);
        }

        @media (max-width: 768px) {
            .menu {
                flex-wrap: wrap;
                gap: 0.75rem;
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
    </div>

    <div class="menu-bar">
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="submit.php">Submit Code</a>
            <a href="api-docs.php">API Docs</a>
            <a href="changelog.php">Changelog</a>
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

            <label for="referralCode">Referral Code<br>(Just the code - ex. ZAK1452284)</label>
            <input type="text" id="referralCode" name="referralCode" required>
            <br>
            <button type="submit">Submit</button>
        </form>
        <br>
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>
