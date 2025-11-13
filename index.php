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
    <meta name="description" content="Get Rivian referral codes and earn rewards on your purchase. Join the Code to Adventure community.">
    <title>Code to Adventure - Rivian Referral Codes</title>
    
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
            width: 80px;
            height: 80px;
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
        
        /* Hero Section */
        .hero {
            padding: 4rem 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, var(--surface), transparent);
        }
        
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem 4rem;
        }
        
        /* Code Card */
        .code-card {
            max-width: 600px;
            margin: -2rem auto 3rem;
            background: var(--surface);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 20px 25px -5px var(--shadow), 0 10px 10px -5px var(--shadow);
            border: 1px solid var(--border);
        }
        
        .code-display {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .code-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .code-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .code-value {
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            letter-spacing: 0.1em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .referrer-name {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .code-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn {
            flex: 1;
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
        }
        
        .btn-secondary:hover {
            background: var(--border);
        }
        
        .btn-icon {
            font-size: 1.25rem;
        }
        
        .info-text {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .info-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.75rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px var(--shadow);
        }
        
        .info-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .info-card h3 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            color: var(--text);
        }
        
        .info-card p {
            color: var(--text-muted);
            line-height: 1.6;
        }
        
        .reward-amount {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
            margin: 1rem 0 0.5rem;
        }
        
        .reward-detail {
            font-size: 0.875rem;
            color: var(--text-muted);
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
        
        .footer-copy {
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-sm);
            box-shadow: 0 10px 25px -5px var(--shadow);
            z-index: 1000;
            display: none;
            animation: slideUp 0.3s ease;
        }
        
        .toast.show {
            display: block;
        }
        
        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
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
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .code-value {
                font-size: 1.5rem;
            }
            
            .code-actions {
                flex-direction: column;
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
                <a href="index.php" class="nav-link active">Home</a>
                <a href="submit.php" class="nav-link">Submit Code</a>
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
            </div>
            
            <button onclick="location.reload()" class="btn btn-secondary" style="width: 100%;">
                <span class="btn-icon">🔄</span>
                <span>Get Another Code</span>
            </button>
            
            <p class="info-text" style="margin-top: 1rem;">
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
            © 2024-2025 Code to Adventure. Not affiliated with Rivian.
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