<?php
include 'config.php';

// Fetch random referral data from the database
$sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);
$referral = $result->fetch_assoc();

// Define the directory that contains the header images
$imageDir = 'header-images/';

// Get all image files in the directory (e.g., png, jpg, jpeg, gif)
$images = glob($imageDir . '*.{png,jpg,jpeg,gif}', GLOB_BRACE);

// Randomly select an image from the array
if ($images && count($images) > 0) {
    $randomImage = $images[array_rand($images)];
} else {
    $randomImage = 'default-image.png'; // Fallback image if no images are found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Code to Adventure is the best place to find and submit Rivian referral codes. Discover and share referral codes to enjoy Rivian's special offers.">
    <meta name="keywords" content="Rivian referral codes, submit referral codes, Rivian offers, electric vehicles, EV referral, Code to Adventure, Rivian community">
    <meta name="author" content="Code to Adventure">
    <meta property="og:title" content="Code to Adventure - Random Rivian Referral Codes">
    <meta property="og:description" content="Discover and share Rivian referral codes to enjoy special offers. Submit your referral code or use one today!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://codetoadventure.com">
    <meta property="og:image" content="https://codetoadventure.com/lightbar-fade.png">
    <meta property="og:site_name" content="Code to Adventure">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Code to Adventure - Random Rivian Referral Codes">
    <meta name="twitter:description" content="Discover and share Rivian referral codes to enjoy special offers. Submit your referral code or use one today!">
    <meta name="twitter:image" content="https://codetoadventure.com/lightbar-fade.png">
    <link rel="canonical" href="https://codetoadventure.com">
    <meta name="robots" content="index, follow">

    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>

    <title>Code to Adventure - Random Rivian Referral Codes</title>

    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Ensure padding and border are included in width and height */
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden; /* Disable horizontal scroll */
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 100vh; /* Ensure body takes full viewport height */
            text-align: center;
        }

        header {
            background-color: #046896;
            padding: 0;
            width: 100%;
            text-align: center;
        }

        header img {
            width: 100%;
            max-width: 1200px; /* Allow larger width for desktop */
            height: auto;
        }

        nav {
            background-color: #B4232A;
            padding: 10px;
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap; /* Allow wrapping on mobile */
            box-sizing: border-box;
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 1200px; /* Full width for larger screens */
            width: 100%;
            padding: 0 20px;
        }

        .description {
            font-size: 22px;
            margin-bottom: 50px;
        }

        .referral-info {
            font-size: 24px;
            margin-bottom: 50px;
            background-color: #1A1A1A; /* Background for referral info */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%; /* Ensure it takes full width */
            max-width: 600px; /* Limit the max width */
        }

        .referral-info a {
            color: #00acee; /* Blue link color */
            text-decoration: none;
        }

        .referral-info a:hover {
            text-decoration: underline;
        }

        .referral-details {
            text-align: left;
            margin-bottom: 50px;
        }

        .referral-details p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .referral-details ul {
            list-style-type: disc;
            margin-left: 20px;
        }

        .referral-details ul li {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .button {
            background-color: #046896;
            color: #E7E7E5;
            padding: 15px 40px;
            font-size: 22px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 40px;
            width: auto;
            max-width: 100%;
            box-sizing: border-box;
        }

        .button:hover {
            background-color: #B4232A;
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            width: 100%;
            text-align: center;
            font-size: 16px;
        }

        footer a {
            color: #00acee; /* Header blue color */
            text-decoration: none;
        }

        footer a:visited {
            color: #B4232A; /* Red for visited links */
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile-friendly adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 50px; /* Add padding to push content further down */
            }

            .button {
                padding: 12px 20px;
                font-size: 18px;
                width: 90%;
            }

            .main-content {
                padding-bottom: 50px; /* Add space at the bottom to avoid overlap */
            }
<<<<<<< HEAD
=======

            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }
>>>>>>> e2a0598347daa7c6b1e16ed4c5003da2f2fc6352
        }
    </style>
</head>
<body>
<style>
    /* Promo Banner Style */
    .promo-banner {
        background-color: #DEB526; /* Banner background color */
        color: #FFFFFF; /* Text color */
        font-size: 16px;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9999;
    }

    .promo-banner .promo-text {
        flex: 1;
        text-align: left;
    }

    .promo-banner .promo-code {
        background-color: #FFFFFF;
        color: #DEB526;
        border: 2px solid #DEB526;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .promo-banner .promo-code:hover {
        background-color: #DEB526;
        color: #FFFFFF;
    }

    .promo-banner .clipboard-icon {
        cursor: pointer;
        margin-left: 10px;
    }

    .promo-banner .clipboard-icon:hover {
        color: #B4232A;
    }

</style>

<div class="promo-banner">
    <div class="promo-text">
        Use code <span id="promo-code">ZAK1452284</span> to get 750 points ($750 value) + 6 months free Rivian Adventure Network charging when ordering your R1!
    </div>
    <div>
        <button class="promo-code" id="copy-code-btn">Copy Code</button>
        <span class="clipboard-icon" id="copy-icon">&#x2398;</span> <!-- Clipboard icon -->
    </div>
</div>

<script>
    // Clipboard functionality for copying the code
    document.getElementById('copy-code-btn').addEventListener('click', function() {
        var promoCode = document.getElementById('promo-code').textContent;
        var textArea = document.createElement('textarea');
        textArea.value = promoCode;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Promo code copied: ' + promoCode);
    });

    // Optional: Clicking the clipboard icon also copies the code
    document.getElementById('copy-icon').addEventListener('click', function() {
        var promoCode = document.getElementById('promo-code').textContent;
        var textArea = document.createElement('textarea');
        textArea.value = promoCode;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Promo code copied: ' + promoCode);
    });
</script>
<header>
    <img src="<?php echo $randomImage; ?>" alt="Header Image">
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="submit.php">Submit Code</a>
    <a href="api-docs.html">API Docs</a>
    <a href="changelog.html">Changelog</a>
    <a href="admin/">Admin</a>
</nav>

<div class="main-content">
    <div class="description">
        Welcome to Code to Adventure, your go-to site for finding and sharing Rivian referral codes. Submit your own code or discover codes from others to enjoy exclusive Rivian offers.
    </div>

    <div class="referral-info">
        <?php if ($referral): ?>
            <strong><?php echo htmlspecialchars($referral['name']); ?></strong>
            (<a href="https://x.com/<?php echo htmlspecialchars($referral['username']); ?>" target="_blank">@<?php echo htmlspecialchars($referral['username']); ?></a>) - 
            <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($referral['referral_code']); ?>" target="_blank">Use this Referral Code</a>
        <?php else: ?>
            <div>No referral codes available yet!</div>
        <?php endif; ?>
    </div>

    <div class="referral-details">
        <p>
            When someone uses an owner’s referral code during checkout of a qualifying R1 Shop vehicle, then takes delivery – both the original owner (referrer) and new owner (referee) get rewards!
        </p>
        <ul>
            <li>750 points** that can be redeemed in Gear Shop or R1 Shop (1 point equals 1 dollar in credit)</li>
            <li>6 months of charging at Rivian Adventure Network sites (up to a lifetime limit of three years)</li>
        </ul>
    </div>

    <a href="submit.php" class="button">Submit Your Referral Code</a>
</div>

<footer>
    <a href="changelog.html" target="_blank">Version 2024.11.13</a> | Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="https://zak.codetoadventure.com" target="_blank">Zak's Referral Code</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

<?php $conn->close(); ?>
</body>
</html>
