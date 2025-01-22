<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Code To Adventure - Random Rivian Referrals</title>
    <meta name="title" content="Code To Adventure - Random Rivian Referrals">
    <meta name="description" content="Get $500 in credit and 6 months free charging with Rivian referral codes for your R1T or R1S purchase. Find valid referral codes from real Rivian owners.">
    <meta name="keywords" content="Rivian, referral code, R1T, R1S, electric vehicles, EV rewards, electric truck, electric SUV">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://codetoadventure.com/">
    <meta property="og:title" content="Get Rivian Referral Rewards - Code To Adventure">
    <meta property="og:description" content="Save $500 and get 6 months free charging on your new Rivian with owner referral codes. Find valid codes instantly.">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://codetoadventure.com/">
    <meta property="twitter:title" content="Get Rivian Referral Rewards - Code To Adventure">
    <meta property="twitter:description" content="Save $500 and get 6 months free charging on your new Rivian with owner referral codes. Find valid codes instantly.">
    
    <!-- Additional SEO -->
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <link rel="canonical" href="https://codetoadventure.com/">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    
    <!-- Deferred scripts -->
    <script src="js/main.js" defer></script>
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>

    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "Code To Adventure",
        "description": "Find and share Rivian referral codes for rewards and discounts on R1T and R1S purchases",
        "url": "https://codetoadventure.com",
        "applicationCategory": "ReferralService",
        "author": {
            "@type": "Person",
            "name": "Zak Winnick",
            "url": "https://winnick.io"
        },
        "offers": {
            "@type": "Offer",
            "description": "Get $500 in Gear Shop credit and 6 months of free charging at Rivian Adventure Network sites"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "ratingCount": "650",
            "bestRating": "5",
            "worstRating": "1"
        }
    }
    </script>

    <!-- Toast Notification Container -->
    <div class="toast" id="toast"></div>
</head>
<body>
    <?php
    include 'config.php';

    // Get random referral code
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $referral = $result->fetch_assoc();
    ?>

    <header class="header">
        <a href="/" class="logo-container">Code To Adventure</a>
        <a href="#" class="submit-button" onclick="showModal(); return false;">Submit Your Code</a>
    </header>

    <nav class="nav-container">
        <div class="nav-content">
            <a href="index.php" class="nav-link">Home</a>
            <a href="#" class="nav-link" onclick="showModal(); return false;">Submit Code</a>
            <a href="api-docs.html" class="nav-link">API Docs</a>
            <a href="changelog.html" class="nav-link">Changelog</a>
            <a href="/admin" class="nav-link">Admin</a>
        </div>
    </nav>

    <main class="main-content">
        <h1 class="hero-title">Buying a Rivian?</h1>
        <h2 class="hero-subtitle">Use a referral code and get rewards!</h2>

        <?php if ($referral): ?>
            <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($referral['referral_code']); ?>" 
               class="referral-button" 
               target="_blank" 
               rel="noopener noreferrer">
                Use <?php echo htmlspecialchars($referral['name']); ?>'s Code
            </a>
            <br>
            <div class="code-container">
                <span class="referral-code"><?php echo htmlspecialchars($referral['referral_code']); ?></span>
                <button class="copy-button" onclick="copyCode('<?php echo htmlspecialchars($referral['referral_code']); ?>')" title="Copy code">
                    <span>⧉</span> Copy Code
                </button>
            </div>
            <button onclick="getNewCode()" class="refresh-button">
                Get Another Code
            </button>
            <p class="refresh-text">You'll be directed to Rivian's R1 Shop. Code changes every page refresh.</p>
        <?php endif; ?>

        <div class="info-section">
            <div>
                <h3 class="info-title">How does it work?</h3>
                <p class="info-text">When you use an owner's referral code during checkout of a qualifying R1 Shop vehicle, then take delivery – both the original owner (referrer) and new owner (referee) get rewards!</p>
            </div>

            <div>
                <h3 class="info-title">What are the rewards?</h3>
                <div class="reward-item">
                    <svg class="reward-icon" viewBox="0 0 24 24">
                        <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 0 0-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2z"/>
                    </svg>
                    <div>
                        <p class="reward-text">500 points that can be redeemed in Gear Shop or R1 Shop</p>
                        <p class="reward-note">(1 point equals 1 dollar in credit)</p>
                    </div>
                </div>
                <div class="reward-item">
                    <svg class="reward-icon" viewBox="0 0 24 24">
                        <path d="M7 2v11h3v9l7-12h-4l4-8z"/>
                    </svg>
                    <div>
                        <p class="reward-text">6 months of free charging at Rivian Adventure Network sites</p>
                        <p class="reward-note">(up to a lifetime limit of three years)</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; <span id="currentYear"></span> 
            <a href='https://winnick.io' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak Winnick</a>
        </p>
        <div class="footer-links">
            <a href='https://zak.codetoadventure.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak's Referral Code</a>
            <a href='changelog.html' class="footer-link" target='_blank' rel='noopener noreferrer'>Version 2025.2</a>
            <a href="mailto:admin@codetoadventure.com" class="footer-link" target='_blank' rel='noopener noreferrer'>E-mail the admin</a>
        </div>
    </footer>

    <!-- Modal Dialog -->
    <div class="modal" id="submitModal">
        <div class="form-container">
            <h1>Submit Your Referral Code</h1>
            <form action="store_code.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="referralCode">Referral Code<br>(Just the code - ex. ZAK1452284)</label>
                <input type="text" id="referralCode" name="referralCode" required>
                <br>
                <button type="submit">Submit</button>
            </form>
            <button class="modal-close" onclick="closeModal()">Cancel</button>
        </div>
    </div>


</body>
</html>