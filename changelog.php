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
    <meta name="description" content="Code to Adventure Changelog - Latest updates and improvements">
    <title>Changelog - Code to Adventure</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
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
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }
        
        /* Version Card */
        .version-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .version-card:hover {
            transform: translateX(4px);
            box-shadow: -4px 0 20px -5px var(--shadow);
        }
        
        .version-card.latest {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--surface), transparent);
        }
        
        .version-card.latest::before {
            content: 'LATEST';
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        
        .version-header {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .version-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            font-family: 'JetBrains Mono', monospace;
        }
        
        .version-date {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .version-changes {
            list-style: none;
            padding: 0;
        }
        
        .version-changes li {
            position: relative;
            padding-left: 1.75rem;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
        }
        
        .version-changes li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-weight: 600;
        }
        
        .version-changes code {
            background: var(--surface-hover);
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
        }
        
        /* Section Divider */
        .section-divider {
            display: flex;
            align-items: center;
            margin: 3rem 0 2rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        
        .section-divider span {
            padding: 0 1rem;
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
            
            .version-header {
                flex-direction: column;
                gap: 0.25rem;
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
                <a href="submit.php" class="nav-link">Submit Code</a>
                <a href="api-docs.php" class="nav-link">API Docs</a>
                <a href="changelog.php" class="nav-link active">Changelog</a>
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
        <h1>Changelog</h1>
        <p>Track all updates, improvements, and new features</p>
    </section>
    
    <!-- Main Content -->
    <main class="container">
        <!-- Latest Version -->
        <div class="version-card latest">
            <div class="version-header">
                <span class="version-number">v2025.35.0</span>
                <span class="version-date">August 27, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Complete redesign of the entire site with modern, clean aesthetics</li>
                <li>Improved visual hierarchy and card-based layout</li>
                <li>Enhanced dark/light mode with smoother transitions</li>
                <li>Added custom logo images that adapt to theme (logo.png and logo-dark.png)</li>
                <li>Integrated Tinylytics analytics for visitor tracking</li>
                <li>Redesigned API documentation with better code examples</li>
                <li>Redesigned Submit Code page with modern form styling</li>
                <li>Updated changelog with improved version display</li>
                <li>Fixed mobile navigation menu functionality with improved CSS specificity</li>
                <li>Removed external Rivian links from footer for cleaner design</li>
                <li>Added gradient accents and modern typography</li>
                <li>Improved mobile responsiveness across all pages</li>
                <li>Added smooth animations and micro-interactions</li>
                <li>Optimized performance and reduced CSS footprint</li>
            </ul>
        </div>
        
        <!-- Previous Versions -->
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.9.1</span>
                <span class="version-date">August 11, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Removed admin notification of successful code submission</li>
                <li>Minor fixes and optimizations</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.9</span>
                <span class="version-date">March 30, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Fixed issue with duplicate toast notifications appearing during code submission</li>
                <li>Improved error handling for the code submission process</li>
                <li>Enhanced form validation with better user feedback</li>
                <li>Added better debugging capabilities for administrators</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.8</span>
                <span class="version-date">March 26, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Due to changes in the Rivian Referral program, sharing of codes is no longer allowed</li>
                <li>Updated the site to reflect the changes in the program</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.7.3</span>
                <span class="version-date">March 11, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Updated <code>Rewards</code> section to reflect changes to the program</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.7.2</span>
                <span class="version-date">February 19, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Introduced the new site logo, and implemented it in the header (both light and dark mode)</li>
            </ul>
        </div>
        
        <div class="section-divider">
            <span>Earlier Versions</span>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.7.1</span>
                <span class="version-date">February 6, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Fixed modal appearance on API Docs and Changelog pages</li>
                <li>Fixed a bug where you were not able to click/tap out of the Submit Code modal</li>
                <li>Centered toast notifications for better visibility</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.7</span>
                <span class="version-date">February 5, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Updated design language of the entire site to make it look more clean</li>
                <li>Added light/dark mode with toggle and auto-detect state</li>
                <li>Improved mobile formatting for navigation, header, and footer</li>
                <li>Completely overhauled <code>api-docs.php</code> and <code>changelog.php</code> for clarity, styling, and accuracy</li>
                <li>Optimized JavaScript and CSS for better load times</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.6</span>
                <span class="version-date">February 4, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Updated API to v2 (see API Documentation for more info)</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.5.1</span>
                <span class="version-date">February 1, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Fixed code to schedule X posts for every 4 hours to work around X API limits</li>
                <li>Added a new favicon to the header</li>
            </ul>
        </div>
        
        <div class="version-card">
            <div class="version-header">
                <span class="version-number">v2025.5</span>
                <span class="version-date">January 31, 2025</span>
            </div>
            <ul class="version-changes">
                <li>Each newly submitted code is now posted to @CodeToAdventure on X</li>
            </ul>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="index.php" class="footer-link">Home</a>
            <a href="api-docs.php" class="footer-link">API Documentation</a>
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