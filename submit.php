<?php
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Submit your Rivian referral code to Code to Adventure">
    <title>Submit Code - Code to Adventure</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Analytics -->
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --background: #ffffff;
            --surface: #f8fafc;
            --surface-hover: #f1f5f9;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --radius-sm: 8px;
            --success: #10b981;
            --error: #ef4444;
        }
        
        [data-theme="dark"] {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #34d399;
            --accent: #fbbf24;
            --background: #0f172a;
            --surface: #1e293b;
            --surface-hover: #334155;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --shadow: rgba(0, 0, 0, 0.3);
            --success: #34d399;
            --error: #f87171;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .nav-link {
            padding: 0.5rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .nav-link:hover {
            background: var(--surface-hover);
            color: var(--primary);
        }
        
        .nav-link.active {
            background: var(--primary);
            color: white;
        }
        
        .theme-toggle {
            background: var(--surface-hover);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: var(--text);
            margin-left: 0.5rem;
        }
        
        .theme-toggle:hover {
            background: var(--border);
        }
        
        /* Hero */
        .hero {
            padding: 3rem 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, var(--surface), transparent);
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 1.125rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Container */
        .container {
            max-width: 600px;
            margin: -2rem auto 4rem;
            padding: 0 1.5rem;
        }
        
        /* Form Card */
        .form-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 20px 25px -5px var(--shadow), 0 10px 10px -5px var(--shadow);
            border: 1px solid var(--border);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text);
        }
        
        .form-hint {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--background);
            color: var(--text);
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px var(--shadow);
        }
        
        .btn-secondary {
            background: var(--surface-hover);
            color: var(--text);
            margin-top: 1rem;
        }
        
        .btn-secondary:hover {
            background: var(--border);
        }
        
        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--error);
            color: var(--error);
        }
        
        .alert-icon {
            font-size: 1.25rem;
        }
        
        /* Info Section */
        .info-section {
            margin-top: 3rem;
            padding: 1.5rem;
            background: var(--background);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }
        
        .info-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text);
        }
        
        .info-list {
            list-style: none;
            padding: 0;
        }
        
        .info-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }
        
        .info-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 2rem 1.5rem;
            text-align: center;
            margin-top: 4rem;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        
        .footer-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }
        
        .footer-link:hover {
            color: var(--primary);
        }
        
        /* Mobile Menu Button - Hidden by default */
        .mobile-menu-btn {
            display: none;
            background: var(--surface-hover);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            color: var(--text);
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            /* Hide desktop nav on mobile */
            .nav {
                display: none !important;
            }
            
            /* Show mobile menu button */
            .mobile-menu-btn {
                display: flex !important;
            }
            
            /* Mobile menu when open */
            .nav.mobile-open {
                display: flex !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--surface);
                flex-direction: column;
                padding: 1rem;
                border-bottom: 1px solid var(--border);
                box-shadow: 0 4px 6px -1px var(--shadow);
                z-index: 999;
            }
            
            .nav.mobile-open .nav-link {
                width: 100%;
                text-align: left;
                padding: 0.75rem 1rem;
            }
            
            .nav.mobile-open .theme-toggle {
                width: 100%;
                justify-content: center;
                margin-top: 0.5rem;
            }
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
            <!-- Display Messages -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <span>This referral code already exists in our database. Thank you for contributing!</span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span>Your referral code has been submitted successfully! Thank you for sharing.</span>
                </div>
            <?php endif; ?>
            
            <!-- Form -->
            <form action="store_code.php" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" id="name" name="name" class="form-input" required placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="referralCode" class="form-label">Referral Code</label>
                    <input type="text" id="referralCode" name="referralCode" class="form-input" required placeholder="ZAK1452284">
                    <div class="form-hint">Enter just the code (e.g., ZAK1452284)</div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <span>Submit Code</span>
                    <span>→</span>
                </button>
                
                <a href="index.php" class="btn btn-secondary">
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
            <a href="https://zak.codetoadventure.com" class="footer-link" target="_blank">Zak's Referral Code</a>

        </div>
        <div class="footer-copy">
            © 2024-2025 Code to Adventure. Not affiliated with Rivian.
        </div>
    </footer>
    
    <script>
        // Theme Toggle
        function initTheme() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            
            document.documentElement.setAttribute('data-theme', theme);
            updateThemeIcon(theme);
            updateLogo(theme);
        }
        
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', next);
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
        
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('nav').classList.toggle('mobile-open');
        }
        
        // Initialize theme on load
        initTheme();
    </script>
</body>
</html>