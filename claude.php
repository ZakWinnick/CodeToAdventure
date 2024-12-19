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
        /* CSS Custom Properties for consistent theming */
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

        /* Base styles and reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            line-height: 1.6;
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto auto 1fr auto;
        }

        /* Header Styles */
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
            gap: 1rem;
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

        /* Navigation Styles */
        .nav-container {
            width: 100%;
            background-color: var(--secondary-bg);
            padding: 0.5rem;
        }

        .nav-content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-link {
            color: var(--primary-text);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
            font-size: 0.9375rem;
        }

        .nav-link:hover {
            background-color: var(--accent-hover);
            color: var(--primary-bg);
        }

        /* Main Content Styles */
        .main-content {
            max-width: var(--max-width);
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .hero-section {
            text-align: center;
            margin: 3rem 0;
        }

        .hero-title {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .referral-section {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 2rem 0;
        }

        .referral-code {
            font-size: 2rem;
            color: var(--accent-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .referral-name {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .shop-link {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--primary-bg);
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition-speed) ease;
        }

        .shop-link:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Information Sections */
        .info-section {
            margin: 4rem 0;
        }

        .info-title {
            font-size: 1.75rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .reward-card {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            transition: transform var(--transition-speed) ease;
        }

        .reward-card:hover {
            transform: translateY(-5px);
        }

        .reward-title {
            font-size: 1.5rem;
            color: var(--primary-text);
            margin-bottom: 1rem;
        }

        .reward-description {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }

        .reward-note {
            font-size: 0.9rem;
            color: var(--accent-color);
            opacity: 0.8;
        }

        /* Footer Styles */
        .footer {
            background-color: var(--secondary-bg);
            padding: 2rem 1rem;
            margin-top: 4rem;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                overflow-x: hidden; /* Prevent horizontal scroll */
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                padding: 0.75rem;
            }

            .submit-button {
                width: 100%;
                max-width: 300px;
            }

            .nav-container {
                padding: 0.5rem 0;
            }

            .nav-content {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0.25rem;
                width: 100%;
                padding: 0 0.5rem;
            }

            .nav-link {
                text-align: center;
                padding: 0.625rem 0.5rem;
                font-size: 0.875rem;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 44px; /* Ensure good touch target size */
            }

            .hero-title {
                font-size: clamp(1.75rem, 3vw, 2.5rem);
            }

            .referral-section {
                padding: 1.5rem;
            }

            .referral-code {
                font-size: 1.5rem;
                word-break: break-all;
            }

            .rewards-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .footer-links {
                gap: 1rem;
                padding: 0 0.5rem;
            }
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <?php
    // Include database configuration
    if (!file_exists('config.php')) {
        die('Configuration file not found');
    }
    include 'config.php';

    // Initialize referral variable
    $referral = null;

    // Fetch random referral data from the database
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
        <section class="hero-section animate-in">
            <h1 class="hero-title">Buying a Rivian?<br>Use a referral code and <strong>get rewards</strong>!</h1>
        </section>

        <section class="referral-section animate-in">
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

        <section class="info-section">
            <h2 class="info-title">How does it work?</h2>
            <p>When you use an owner's referral code during checkout of a qualifying R1 Shop vehicle, both the original owner (referrer) and new owner (referee) get rewards!</p>
            
            <h2 class="info-title">What are the rewards?</h2>
            <div class="rewards-grid">
                <div class="reward-card">
                    <h3 class="reward-title">750 Points</h3>
                    <p class="reward-description">Redeemable in Gear Shop or R1 Shop.</p>
                    <small class="reward-note">(1 point equals 1 dollar in credit)</small>
                </div>
                <div class="reward-card">
                    <h3 class="reward-title">6 Months Charging</h3>
                    <p class="reward-description">At Rivian Adventure Network sites.</p>
                    <small class="reward-note">(Up to a lifetime limit of three years)</small>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> 
            <a href='https://zakwinnick.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak Winnick</a>
        </p>
        <div class="footer-links">
            <a href='https://zak.codetoadventure.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak's Referral Code</a>
            <a href='changelog.html' class="footer-link" target='_blank' rel='noopener noreferrer'>Version 2024.12.11</a>
            <a href="mailto:admin@codetoadventure.com" class="footer-link" target='_blank' rel='noopener noreferrer'>E-mail the admin</a>
        </div>
    </footer>
</body>
</html>