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
    <meta name="description" content="Submit your Rivian referral code to Code to Adventure">
    <title>Submit Code - Code to Adventure</title>

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

    <style>
        /* Submit page specific: narrower container */
        .container {
            max-width: 600px;
            margin: -2rem auto 4rem;
        }
    </style>
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
                <a href="index.php" class="nav-link">Home</a>
                <a href="submit.php" class="nav-link active">Submit Code</a>
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

    <!-- Hero -->
    <section class="hero">
        <h1>Submit Your Code</h1>
        <p>Share your Rivian referral code with the community and help others save!</p>
    </section>

    <!-- Main Content -->
    <main class="container">
        <div class="form-card">
            <!-- Alert Container for AJAX messages -->
            <div id="alert-container"></div>

            <!-- Form -->
            <form id="submitCodeForm" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" id="name" name="name" class="form-input" required placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label for="referralCode" class="form-label">Referral Code</label>
                    <input type="text" id="referralCode" name="referralCode" class="form-input" required placeholder="ZAK1452284">
                    <div class="form-hint">Enter just the code (e.g., ZAK1452284)</div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn" style="width: 100%;">
                    <span>Submit Code</span>
                    <span>→</span>
                </button>

                <a href="index.php" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
                    <span>←</span>
                    <span>Back to Home</span>
                </a>
            </form>

            <!-- Info Section -->
            <div class="info-section">
                <h3 class="info-title">Before You Submit</h3>
                <ul class="info-list">
                    <li>Make sure your code is active and valid</li>
                    <li>Enter only the code itself, without any URLs</li>
                    <li>Your code will be randomly displayed to visitors</li>
                    <li>Both you and the new buyer receive rewards</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="index.php" class="footer-link">Home</a>
            <a href="api-docs.php" class="footer-link">API Documentation</a>
            <a href="changelog.php" class="footer-link">Changelog</a>
            <a href="https://zak.codetoadventure.com" target="_blank" class="footer-link">Zak's Referral Code</a>
        </div>
        <div class="footer-copy">
            © 2024-<?php echo date('Y'); ?> Code to Adventure. Not affiliated with Rivian.
        </div>
    </footer>

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

        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateThemeIcon(theme);
            updateLogo(theme);
        }

        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only auto-switch if user hasn't manually set a preference
            if (!localStorage.getItem('theme')) {
                setTheme(e.matches ? 'dark' : 'light');
            }
        });

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('nav').classList.toggle('mobile-open');
        }

        // Initialize theme on load
        initTheme();

        // Handle form submission with AJAX
        document.getElementById('submitCodeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alert-container');

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Submitting...</span>';

            try {
                // Send AJAX request
                const response = await fetch('store_code.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                // Clear any existing alerts
                alertContainer.innerHTML = '';

                if (data.success) {
                    // Show success message
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <span class="alert-icon">✓</span>
                            <span>${data.message || 'Your referral code has been submitted successfully!'}</span>
                        </div>
                    `;

                    // Clear form
                    document.getElementById('name').value = '';
                    document.getElementById('referralCode').value = '';

                    // Scroll to top to show message
                    alertContainer.scrollIntoView({ behavior: 'smooth' });
                } else {
                    // Show error message
                    alertContainer.innerHTML = `
                        <div class="alert alert-error">
                            <span class="alert-icon">⚠️</span>
                            <span>${data.message || 'An error occurred. Please try again.'}</span>
                        </div>
                    `;
                }
            } catch (error) {
                // Show error message for network errors
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠️</span>
                        <span>Failed to submit code. Please check your connection and try again.</span>
                    </div>
                `;
                console.error('Submission error:', error);
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Submit Code</span><span>→</span>';
            }
        });
    </script>
</body>
</html>
