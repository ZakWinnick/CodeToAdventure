<?php
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body>
    <?php
    // Get random referral code
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $referral = $result->fetch_assoc();
    ?>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <main class="main-content">
        <h1 class="hero-title">Buying a Rivian?</h1>
        <h2 class="hero-subtitle">Use a referral code and get rewards!</h2>

        <?php if ($referral): ?>
            <a href="track.php?code=<?php echo htmlspecialchars($referral['referral_code']); ?>" 
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

   <?php include 'includes/footer.php'; ?>
    <?php include 'includes/modal.php'; ?>
    
    <script src="js/main.js"></script>
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>
</body>
</html>