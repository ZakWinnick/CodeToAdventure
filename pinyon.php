<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find and use a random Rivian referral code to enjoy rewards on your Rivian purchase. Explore the Code to Adventure website and submit your own referral code!">
    <meta name="keywords" content="Rivian, referral code, rewards, electric vehicles, shop Rivian">
    <meta name="author" content="Zak Winnick">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://codetoadventure.com/">
    <meta property="og:title" content="Code to Adventure - Random Rivian Referrals">
    <meta property="og:description" content="Discover and use random Rivian referral codes to unlock exciting rewards! Submit your own code too.">
    <meta property="og:image" content="https://codetoadventure.com/assets/og-image.jpg">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://codetoadventure.com/">
    <meta name="twitter:title" content="Code to Adventure - Random Rivian Referrals">
    <meta name="twitter:description" content="Use random Rivian referral codes for rewards! Submit your code to be featured.">
    <meta name="twitter:image" content="https://codetoadventure.com/assets/twitter-card.jpg">

    <title>Code to Adventure - Random Rivian Referrals</title>
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
            width: 100%; max-width: 1200px;
            padding: 1rem 1.5rem;
            background-color: #123A13;
            color: #E7E7E5;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background-color 0.3s;
        }

        .title-bar button:hover {
            background-color: #6f946f;
        }

        header {
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        header h1 {
            font-size: 2.5rem;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        header p {
            font-size: 1.25rem;
            font-weight: 500;
        }

        .referral-display {
            text-align: center;
            margin-bottom: 2rem;
        }

        .referral-code {
            font-size: 1.5rem;
            font-weight: bold;
            color: #87b485;
        }

        .referee-name {
            margin: 0.5rem 0;
            color: #E7E7E5;
        }

        .shop-link {
            display: inline-block;
            background-color: #87b485;
            color: #142a13;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 1.25rem;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .shop-link:hover {
            background-color: #6f946f;
        }

        .content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .section {
            margin-bottom: 3rem;
        }

        .section h2 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: #87b485;
            text-align: left;
        }

        .section p {
            font-size: 1.25rem;
            line-height: 1.6;
            text-align: left;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .bottom-text {
            background-color: #1a3e2b;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: left;
            flex: 1 1 300px;
            color: #87b485;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .bottom-text h3 {
            color: #E7E7E5;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .bottom-text p {
            margin: 0.5rem 0;
        }

        .bottom-text small {
            font-size: 0.9rem;
            color: #87b485;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #1a3e2b;
            color: #E7E7E5;
            width: 100%;
            margin-top: auto;
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

            .menu a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'config.php';

    // Fetch random referral data from the database
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $referral = $result->fetch_assoc();
    ?>

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

    <header>
        <h1>Buying a Rivian?<br>Use a referral code and <strong>get rewards</strong>!</h1>
    </header>

    <div class="referral-display">
        <p class="referral-code">Code: <span><?php echo $referral['referral_code']; ?></span></p>
        <p class="referee-name">Submitted by: <span><?php echo $referral['name']; ?></span></p>
        <a href="https://rivian.com/configurations/list?reprCode=<?php echo $referral['referral_code']; ?>" target="_blank" class="shop-link">Shop with this Code</a>
    </div>

    <main class="content">
        <section class="section">
            <h2>How does it work?</h2>
            <p>When you use an owner’s referral code during checkout of a qualifying R1 Shop vehicle, both the original owner (referrer) and new owner (referee) get rewards!</p>
        </section>

        <section class="section">
            <h2>What are the rewards?</h2>
            <div class="bottom-section">
                <div class="bottom-text">
                    <h3>750 Points</h3>
                    <p>Redeemable in Gear Shop or R1 Shop.</p>
                    <small>(1 point equals 1 dollar in credit)</small>
                </div>
                <div class="bottom-text">
                    <h3>6 Months Charging</h3>
                    <p>At Rivian Adventure Network sites.</p>
                    <small>(Up to a lifetime limit of three years)</small>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <script>document.write(new Date().getFullYear());</script> <a href='https://zak.codetoadventure.com' style='color: #87b485; text-decoration: none;' target='_blank' rel='noopener noreferrer'>Zak Winnick</a></p>
    </footer>
</body>
</html>
