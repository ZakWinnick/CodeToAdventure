<?php
session_start();
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in as admin
$isAdmin = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Get Rivian referral codes and earn rewards on your purchase. Join the Code to Adventure community.">
    <title>Code to Adventure - Rivian Referral Codes</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.png">

    <!-- Dark mode initialization - Must come before CSS -->
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = saved || (prefersDark ? 'dark' : 'light');
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="styles/base/_variables.css">
    <link rel="stylesheet" href="styles/base/_reset.css">
    <link rel="stylesheet" href="styles/pages/_shared.css">

    <!-- Analytics -->
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <img src="logo.png" alt="Code to Adventure" id="logo-img">
                </div>
                <div class="logo-text">Code to Adventure</div>
            </a>

            <nav class="nav" id="nav">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="submit.php" class="nav-link">Submit Code</a>
                <a href="api-docs.php" class="nav-link">API Docs</a>
                <a href="changelog.php" class="nav-link">Changelog</a>
                <?php if ($isAdmin): ?>
                <a href="admin/admin.php" class="nav-link admin-link">Admin</a>
                <?php endif; ?>
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                    <span id="theme-icon">🌙</span>
                </button>
            </nav>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Buying a Rivian?</h1>
        <p>Get exclusive referral codes and unlock rewards on your new adventure vehicle!</p>
    </section>

    <!-- Main Content -->
    <main class="container">
        <?php
        $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
        $referral = $result->fetch_assoc();

        // Increment display count for this code
        if ($referral) {
            $updateStmt = $conn->prepare("UPDATE codes SET display_count = display_count + 1 WHERE id = ?");
            $updateStmt->bind_param("i", $referral['id']);
            $updateStmt->execute();
        }
        ?>

        <?php if ($referral): ?>
        <!-- Code Card -->
        <div class="code-card">
            <div class="code-display">
                <div class="code-label">YOUR REFERRAL CODE</div>
                <div class="code-value"><?php echo htmlspecialchars($referral['referral_code']); ?></div>
                <div class="referrer-name">Shared by <?php echo htmlspecialchars($referral['name']); ?></div>
            </div>

            <div class="code-actions">
                <a href="track.php?code=<?php echo htmlspecialchars($referral['referral_code']); ?>"
                   class="btn btn-primary"
                   target="_blank">
                    <span>Use This Code</span>
                    <span class="btn-icon">→</span>
                </a>
                <button onclick="copyCode('<?php echo htmlspecialchars($referral['referral_code']); ?>')"
                        class="btn btn-secondary">
                    <span class="btn-icon">📋</span>
                    <span>Copy</span>
                </button>
                <button onclick="location.reload()" class="btn btn-secondary">
                    <span class="btn-icon">🔄</span>
                    <span>Get Another Code</span>
                </button>
            </div>

            <p class="info-text mt-2">
                You'll be directed to Rivian's R1 Shop • Code refreshes on reload
            </p>
        </div>
        <?php endif; ?>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon">🚀</div>
                <h3>How It Works</h3>
                <p>Use an owner's referral code during checkout of a qualifying R1 Shop vehicle. When an order is placed, both you and the original owner receive Rivian Rewards points plus 3 months of charging on the Rivian Adventure Network!</p>
            </div>

            <div class="info-card">
                <div class="info-icon">🎁</div>
                <h3>Your Rewards</h3>
                <span class="reward-amount">100-500 Points</span>
                <p class="reward-detail">500 pts: Tri or Quad<br>250 pts: Dual Large/Max battery<br>100 pts: Dual Standard<br>+ 3 months Adventure Network charging</p>
            </div>

            <div class="info-card">
                <div class="info-icon">⚡</div>
                <h3>Owner Rewards</h3>
                <span class="reward-amount">250 Points</span>
                <p class="reward-detail">Earned when order is placed<br>+ 3 months Adventure Network charging<br>(1 point = $1 credit)</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="api-docs.php" class="footer-link">API Documentation</a>
            <a href="changelog.php" class="footer-link">Changelog</a>
            <a href="https://zak.codetoadventure.com" class="footer-link" target="_blank">Zak's Referral Code</a>
        </div>
        <div class="footer-copy">
            © 2024-<?php echo date('Y'); ?> Code to Adventure. Not affiliated with Rivian.
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span id="toast-message">Code copied to clipboard!</span>
    </div>

    <script>
        // Theme Toggle
        function initTheme() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            updateThemeIcon(theme);
            updateLogo(theme);
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', next);
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', next);
            updateThemeIcon(next);
            updateLogo(next);
        }

        function updateThemeIcon(theme) {
            document.getElementById('theme-icon').textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        function updateLogo(theme) {
            const logoImg = document.getElementById('logo-img');
            logoImg.src = theme === 'dark' ? 'logo-dark.png' : 'logo.png';
        }

        // Copy Code Function
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                showToast('Code copied to clipboard!');
            }).catch(() => {
                const input = document.createElement('input');
                input.value = code;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                showToast('Code copied to clipboard!');
            });
        }

        // Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = message;
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('nav').classList.toggle('mobile-open');
        }

        // Initialize theme on load
        initTheme();
    </script>
</body>
</html>
