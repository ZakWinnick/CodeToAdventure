<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code to Adventure - Random Rivian Referrals</title>
    
    <!-- Preload key resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Deferred analytics -->
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Discover Rivian referral codes and get rewards like 750 points for the Gear Shop or 6 months of free charging. Submit your code today!">
    <meta name="keywords" content="Rivian, referral codes, Rivian rewards, Rivian discounts, Rivian referral program, Rivian Adventure Network">
    <meta name="author" content="Zak Winnick">
    <link rel="canonical" href="https://codetoadventure.com">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Code to Adventure - Random Rivian Referrals">
    <meta property="og:description" content="Buying a Rivian? Use a referral code and get rewards like 750 points for the Gear Shop or 6 months of free charging.">
    <meta property="og:image" content="https://codetoadventure.com/lightbar-fade.png">
    <meta property="og:url" content="https://codetoadventure.com">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Code to Adventure - Random Rivian Referrals">
    <meta name="twitter:description" content="Discover Rivian referral codes and get rewards like 750 points for the Gear Shop or 6 months of free charging. Submit your code today!">
    <meta name="twitter:image" content="https://codetoadventure.com/lightbar-fade.png">
    <meta name="twitter:site" content="@zakwinnick">

    <style>
        :root {
            --primary-bg: #142a13;
            --secondary-bg: #1a3e2b;
            --header-bg: #123A13;
            --primary-text: #E7E7E5;
            --accent-color: #87b485;
            --accent-hover: #6f946f;
            --highlight-color: #DEB526;
            --max-width: 1200px;
            --border-radius: 8px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
        }

        body {
            min-height: 100vh;
            position: relative;
            padding-bottom: 4rem; /* Leave space for the Pi symbol */
            overflow-x: hidden;
        }

        .header-container {
            background-color: var(--header-bg);
            padding: 1rem;
            width: 100%;
        }

        .header-content {
            max-width: var(--max-width);
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-title {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            color: var(--highlight-color);
            text-decoration: none;
            font-weight: 700;
        }

        .submit-button {
            background-color: var(--accent-color);
            color: var(--primary-bg);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        .submit-button:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        .nav-container {
            width: 100%;
            background-color: var(--secondary-bg);
            padding: 0.5rem;
        }

        .nav-content {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            max-width: var(--max-width);
            margin: 0 auto;
        }

        .nav-link {
            color: var(--primary-text);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
        }

        .nav-link:hover {
            background-color: var(--accent-hover);
            color: var(--primary-bg);
        }

        .main-content {
            max-width: var(--max-width);
            margin: 2rem auto;
            padding: 0 1rem;
            flex: 1;
        }

        .footer {
            background-color: var(--secondary-bg);
            padding: 2rem 1rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .footer-link {
            color: var(--accent-color);
            text-decoration: none;
            transition: color var(--transition-speed) ease;
        }

        .footer-link:hover {
            color: var(--accent-hover);
        }

        .pi-link {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 2rem;
            color: var(--accent-color);
            background-color: var(--secondary-bg);
            z-index: 1000;
            padding: 0.5rem;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .pi-link a {
            text-decoration: none;
            color: inherit;
        }

        .pi-link:hover {
            color: var(--accent-hover);
        }

        @media (max-width: 768px) {
            .pi-link {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php
    if (!file_exists('config.php')) {
        die('Configuration file not found');
    }
    include 'config.php';

    $referral = null;
    if (isset($conn) && $conn instanceof mysqli) {
        $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);

        if ($result) {
            $referral = $result->fetch_assoc();
        } else {
            error_log("Database query failed: " . $conn->error);
        }
    } else {
        error_log("Database connection not properly initialized");
    }
    ?>

    <header class="header-container">
        <div class="header-content">
            <a href="/" class="site-title">Code to Adventure</a>
            <button onclick="window.location.href='submit.php';" class="submit-button">Submit Code</button>
        </div>
    </header>

    <nav class="nav-container">
        <div class="nav-content">
            <a href="index.php" class="nav-link">Home</a>
            <a href="submit.php" class="nav-link">Submit Code</a>
            <a href="api-docs.html" class="nav-link">API Docs</a>
            <a href="changelog.html" class="nav-link">Changelog</a>
            <a href="/admin" class="nav-link">Admin</a>
        </div>
    </nav>

    <main class="main-content">
        <section class="hero-section">
            <h1 class="hero-title">Buying a Rivian?<br>Use a referral code and <strong>get rewards</strong>!</h1>
        </section>

        <section class="referral-section">
            <?php if ($referral): ?>
                <p class="referral-code">Code: <span><?php echo htmlspecialchars($referral['referral_code']); ?></span></p>
                <p class="referral-name">Submitted by: <span><?php echo htmlspecialchars($referral['name']); ?></span></p>
                <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($referral['referral_code']); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="shop-link">
                    Shop with this Code
                </a>
            <?php else: ?>
                <p>Unable to fetch a referral code at this time. Please try again later.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> 
            <a href='https://zakwinnick.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak Winnick</a>
        </p>
        <div class="footer-links">
            <a href='https://zak.codetoadventure.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak's Referral Code</a>
            <a href='changelog.html' class="footer-link" target='_blank' rel='noopener noreferrer'>Version 2024.12.18</a>
            <a href="mailto:admin@codetoadventure.com" class="footer-link" target='_blank' rel='noopener noreferrer">E-mail the admin</a>
        </div>
    </footer>

    <div class="pi-link">
        <a href="/admin" title="Admin">
            &#960;
        </a>
    </div>
</body>
</html>
