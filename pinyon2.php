<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
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

    <div class="title-bar">
        <h1>Code to Adventure</h1>
        <button onclick="window.location.href='submit.php';">Submit Code</button>
    </div>

    <header style="background-image: url('<?php echo $randomImage; ?>'); background-size: cover; background-position: center;">
        <h1>Buying a Rivian?<br>Use a referral code and <strong>get rewards</strong>!</h1>
    </header>

    <div class="referral-display">
        <p class="referral-code">Code: <span><?php echo $referral['referral_code']; ?></span></p>
        <p class="referee-name">Submitted by: <span><?php echo $referral['name']; ?></span></p>
        <a href="https://rivian.com/shop?referral=<?php echo $referral['referral_code']; ?>" target="_blank" class="shop-link">Shop with this Code</a>
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
