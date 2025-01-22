<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Resource Hints -->
    <link rel="preload" href="styles/main.css" as="style">
    <link rel="preload" href="js/main.js" as="script">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style">
    
    <!-- Security Headers -->
    <?php
    header("Content-Security-Policy: default-src 'self'; style-src 'self' https://fonts.googleapis.com; script-src 'self' https://tinylytics.app;");
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    ?>

    <!-- Primary Meta Tags with variations -->
    <title>Code To Adventure - Random Rivian Referrals</title>
    <?php
    $meta_descriptions = [
        "Get $500 in credit and 6 months free charging with Rivian referral codes.",
        "Save on your R1T or R1S purchase with verified Rivian owner referrals.",
        "Find instant Rivian referral codes from real owners for your purchase."
    ];
    $random_description = $meta_descriptions[array_rand($meta_descriptions)];
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($random_description); ?>">
    
    <!-- Other meta tags remain the same -->
    
    <!-- Preloaded styles -->
    <link rel="stylesheet" href="styles/main.css">
    
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => console.log('ServiceWorker registered'))
                .catch(error => console.error('ServiceWorker error:', error));
        }
    </script>
</head>
<body>
    <!-- CSRF Token -->
    <?php
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>

    <div class="skip-link">
        <a href="#main-content">Skip to main content</a>
    </div>

    <!-- Rest of the body content -->
    <header class="header" role="banner">
        <!-- Header content -->
    </header>

    <main id="main-content" class="main-content" role="main">
        <!-- Enhanced error handling -->
        <?php if (isset($error)): ?>
            <div class="error-alert" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Enhanced code display with loading states -->
        <?php if ($referral): ?>
            <div class="code-display" aria-live="polite">
                <!-- Code content -->
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer" role="contentinfo">
        <!-- Footer content -->
    </footer>

    <!-- Enhanced modal with keyboard support -->
    <div class="modal" id="submitModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <!-- Modal content -->
    </div>
</body>
</html>