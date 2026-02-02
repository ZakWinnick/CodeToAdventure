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
    <meta name="description" content="Code to Adventure API Documentation - Access referral codes programmatically">
    <title>API Documentation - Code to Adventure</title>
    
    <!-- Always dark mode -->
    <script>
        document.documentElement.setAttribute('data-theme', 'dark');
    </script>

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
            --code-bg: #1e293b;
            --code-text: #e2e8f0;
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
            --code-bg: #0f172a;
            --code-text: #e2e8f0;
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
        
        /* Reuse header styles from index */
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

        .nav-link.admin-link {
            background: var(--accent);
            color: var(--background);
            font-weight: 600;
        }

        .nav-link.admin-link:hover {
            background: var(--accent);
            color: var(--background);
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
        
        /* Section Card */
        .section-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .section-content {
            color: var(--text-muted);
        }
        
        .base-url {
            background: var(--code-bg);
            color: var(--code-text);
            padding: 1rem;
            border-radius: var(--radius-sm);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            margin-top: 1rem;
            word-break: break-all;
        }
        
        /* Endpoint */
        .endpoint {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--background);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }
        
        .endpoint-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .method-badge {
            background: var(--secondary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .endpoint-path {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 500;
        }
        
        .endpoint-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .endpoint-description {
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        
        /* Code Block */
        .code-block {
            background: var(--code-bg);
            border-radius: var(--radius-sm);
            padding: 1.25rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        .code-block pre {
            margin: 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            color: var(--code-text);
            line-height: 1.5;
        }
        
        .code-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        /* Status Table */
        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .status-table th,
        .status-table td {
            text-align: left;
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
        }
        
        .status-table th {
            font-weight: 600;
            color: var(--text);
        }
        
        .status-table td {
            color: var(--text-muted);
        }
        
        .status-code {
            display: inline-block;
            background: var(--surface-hover);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-code.success {
            background: #10b98120;
            color: var(--secondary);
        }
        
        .status-code.error {
            background: #ef444420;
            color: #ef4444;
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
            
            .endpoint-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
    <?php $currentPage = 'api-docs'; include 'includes/header.php'; ?>
    
    <!-- Hero -->
    <section class="hero">
        <h1>API Documentation</h1>
        <p>Access the Code to Adventure API to retrieve referral codes programmatically</p>
    </section>
    
    <!-- Main Content -->
    <main class="container">
        <!-- Overview -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">📚</div>
                <span>Overview</span>
            </h2>
            <div class="section-content">
                <p>The Code to Adventure API provides programmatic access to Rivian referral codes. All requests must use HTTPS and responses are returned in JSON format.</p>
                <div class="base-url">https://codetoadventure.com/api.php</div>
            </div>
        </div>
        
        <!-- Authentication -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">🔐</div>
                <span>Authentication</span>
            </h2>
            <div class="section-content">
                <p>The API currently uses IP-based rate limiting and does not require authentication tokens. This may change in future versions.</p>
            </div>
        </div>
        
        <!-- Endpoints -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">🚀</div>
                <span>Endpoints</span>
            </h2>
            
            <!-- Random Code Endpoint -->
            <div class="endpoint">
                <div class="endpoint-header">
                    <span class="method-badge">GET</span>
                    <span class="endpoint-path">/api.php?path=random</span>
                </div>
                <h3 class="endpoint-title">Fetch Random Referral Code</h3>
                <p class="endpoint-description">Returns a single randomly selected referral code from the database.</p>
                
                <div class="code-label">Example Response</div>
                <div class="code-block">
                    <pre>{
  "name": "John Doe",
  "username": "johndoe123",
  "referral_code": "ABC123",
  "link": "https://rivian.com/configurations/list?reprCode=ABC123"
}</pre>
                </div>
            </div>
            
            <!-- All Codes Endpoint -->
            <div class="endpoint">
                <div class="endpoint-header">
                    <span class="method-badge">GET</span>
                    <span class="endpoint-path">/api.php?path=codes</span>
                </div>
                <h3 class="endpoint-title">Fetch All Referral Codes</h3>
                <p class="endpoint-description">Returns an array of all available referral codes in the database.</p>
                
                <div class="code-label">Example Response</div>
                <div class="code-block">
                    <pre>[
  {
    "name": "John Doe",
    "username": "johndoe123",
    "referral_code": "ABC123",
    "link": "https://rivian.com/configurations/list?reprCode=ABC123"
  },
  {
    "name": "Jane Smith",
    "username": "janesmith456",
    "referral_code": "XYZ789",
    "link": "https://rivian.com/configurations/list?reprCode=XYZ789"
  }
]</pre>
                </div>
            </div>
        </div>
        
        <!-- Rate Limiting -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">⏱️</div>
                <span>Rate Limiting</span>
            </h2>
            <div class="section-content">
                <p>API requests are limited to <strong>100 requests per minute</strong> per IP address to ensure fair usage.</p>
                
                <div class="code-label">Response Headers</div>
                <div class="code-block">
                    <pre>X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1635789600</pre>
                </div>
            </div>
        </div>
        
        <!-- Error Handling -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">⚠️</div>
                <span>Error Responses</span>
            </h2>
            <div class="section-content">
                <p>Errors are returned in a consistent JSON format with appropriate HTTP status codes.</p>
                
                <div class="code-label">Error Response Format</div>
                <div class="code-block">
                    <pre>{
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Too many requests. Please try again later."
  }
}</pre>
                </div>
            </div>
        </div>
        
        <!-- Status Codes -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">📊</div>
                <span>HTTP Status Codes</span>
            </h2>
            <table class="status-table">
                <thead>
                    <tr>
                        <th>Status Code</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="status-code success">200</span></td>
                        <td>Success - Request completed successfully</td>
                    </tr>
                    <tr>
                        <td><span class="status-code">404</span></td>
                        <td>Not Found - Endpoint does not exist</td>
                    </tr>
                    <tr>
                        <td><span class="status-code error">429</span></td>
                        <td>Rate Limit Exceeded - Too many requests</td>
                    </tr>
                    <tr>
                        <td><span class="status-code error">500</span></td>
                        <td>Server Error - Internal server error</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Caching -->
        <div class="section-card">
            <h2 class="section-title">
                <div class="section-icon">💾</div>
                <span>Caching</span>
            </h2>
            <div class="section-content">
                <p>API responses are cached for <strong>5 minutes</strong> to improve performance. Use the <code>ETag</code> header for conditional requests to check if data has changed.</p>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="index.php" class="footer-link">Home</a>
            <a href="changelog.php" class="footer-link">Changelog</a>
            <a href="https://zak.codetoadventure.com" class="footer-link" target="_blank">Zak's Referral Code</a>
        </div>
        <div class="footer-copy">
            © 2024-2025 Code to Adventure. Not affiliated with Rivian.
        </div>
    </footer>
    

    
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('nav').classList.toggle('mobile-open');
        }
    </script>
</body>
</html>