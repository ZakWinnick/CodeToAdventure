<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code To Adventure - Random Rivian Referrals</title>
    
    <!-- Preload key resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Deferred analytics -->
    <script src="https://tinylytics.app/embed/wWu5hJWSQ_r9BAxgohx8.js" defer></script>
    
    <style>
        :root {
            --dark-green: #132A13;
            --hunter-green: #31572C;
            --fern-green: #4F772D;
            --moss-green: #90A955;
            --mindaro: #ECF39E;
            --text-white: #FFFFFF;
            --button-radius: 40px;
            --max-width: 1200px;
            --border-radius: 8px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--dark-green);
            color: var(--text-white);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: var(--max-width);
            margin: 0 auto;
            width: 100%;
        }

        .logo-container {
            background-color: var(--mindaro);
            padding: 8px 16px;
            border-radius: 12px;
            color: var(--dark-green);
            font-weight: bold;
            text-decoration: none;
        }

        .submit-button {
            background-color: var(--mindaro);
            color: var(--dark-green);
            padding: 8px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            background-color: var(--moss-green);
        }

        /* Navigation Styles */
        .nav-container {
            width: 100%;
            background-color: var(--hunter-green);
            padding: 0.75rem;
            margin-bottom: 2rem;
        }

        .nav-content {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-link {
            color: var(--mindaro);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 600;
            padding: 0.5rem 0;
        }

        .nav-link:hover {
            color: var(--moss-green);
        }

        .main-content {
            flex: 1;
            max-width: var(--max-width);
            margin: 4rem auto;
            padding: 0 2rem;
            text-align: center;
            width: 100%;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: clamp(1.5rem, 3vw, 2.5rem);
            margin-bottom: 3rem;
        }

        .referral-button {
            background-color: var(--mindaro);
            border-radius: var(--button-radius);
            padding: 20px 40px;
            font-size: 1.25rem;
            color: var(--dark-green);
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .referral-button:hover {
            transform: translateY(-2px);
            background-color: var(--moss-green);
        }

        .refresh-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 900px;
            margin: 6rem auto;
            padding: 0 2rem;
        }

        .info-section > div {
            background-color: var(--hunter-green);
            padding: 2rem;
            border-radius: 12px;
            text-align: left;
        }

        .info-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--mindaro);
        }

        .info-text {
            color: var(--text-white);
            font-size: 1.125rem;
            line-height: 1.6;
        }

        .reward-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .reward-icon {
            width: 24px;
            height: 24px;
            fill: var(--mindaro);
        }

        .reward-text {
            font-size: 1.125rem;
            color: var(--text-white);
        }

        .reward-note {
            font-size: 0.875rem;
            color: var(--moss-green);
        }

        .footer {
            background-color: var(--hunter-green);
            padding: 2rem 1rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .footer-link {
            color: var(--mindaro);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            color: var(--moss-green);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
            padding: 1rem;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            background-color: var(--hunter-green);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin: 5% auto;
            position: relative;
        }

        .form-container h1 {
            margin-bottom: 1.5rem;
            color: var(--mindaro);
            font-size: 1.75rem;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-container label {
            text-align: left;
            font-size: 1rem;
            color: var(--text-white);
        }

        .form-container input {
            padding: 0.75rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }

        .form-container button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--dark-green);
            background-color: var(--mindaro);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: var(--moss-green);
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            color: var(--mindaro);
            font-size: 1.5rem;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.5rem;
        }

        .modal-close:hover {
            color: var(--moss-green);
        }

        @media (max-width: 768px) {
            .info-section {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1rem;
            }

            .nav-content {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 0.75rem;
            }

            .form-container {
                margin: 10% auto;
                padding: 1.5rem;
            }

            .form-container h1 {
                font-size: 1.5rem;
            }

            .form-container button {
                font-size: 1rem;
                padding: 0.5rem;
            }

          /* Animation Keyframes */
          @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }                                                                        }

        .animate-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
    </style>
</head>
<body>
    <?php
    include 'config.php';

    // Get random referral code
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $referral = $result->fetch_assoc();
    ?>

    <header class="header">
        <a href="/" class="logo-container">Code To Adventure</a>
        <a href="#" class="submit-button" onclick="showModal(); return false;">Submit Your Code</a>
    </header>

    <nav class="nav-container">
        <div class="nav-content">
            <a href="index.php" class="nav-link">Home</a>
            <a href="#" class="nav-link" onclick="showModal(); return false;">Submit Your Code</a>
            <a href="api-docs.html" class="nav-link">API Docs</a>
            <a href="changelog.html" class="nav-link">Changelog</a>
            <a href="/admin" class="nav-link">Admin</a>
        </div>
    </nav>

    <main class="main-content">
        <h1 class="hero-title">Buying a Rivian?</h1>
        <h2 class="hero-subtitle">Use a referral code and get rewards!</h2>

        <?php if ($referral): ?>
            <a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($referral['referral_code']); ?>" 
               class="referral-button" 
               target="_blank" 
               rel="noopener noreferrer">
                Use <?php echo htmlspecialchars($referral['name']); ?>'s Code
            </a>
            <p class="refresh-text">You'll be directed to Rivian's R1 Shop. Code changes every page refresh.</p>
        <?php endif; ?>

        <div class="info-section">
            <div>
                <h3 class="info-title">How does it work?</h3>
                <p class="info-text">When you use an owner's referral code during checkout of a qualifying R1 Shop vehicle, then takes delivery – both the original owner (referrer) and new owner (referee) get rewards!</p>
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
                        <p class="reward-text">6 months of charging at Rivian Adventure Network sites</p>
                        <p class="reward-note">(up to a lifetime limit of three years)</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; <script>document.write(new Date().getFullYear());</script> 
            <a href='https://zakwinnick.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak Winnick</a>
        </p>
        <div class="footer-links">
            <a href='https://zak.codetoadventure.com' class="footer-link" target='_blank' rel='noopener noreferrer'>Zak's Referral Code</a>
            <a href='changelog.html' class="footer-link" target='_blank' rel='noopener noreferrer'>Version 2024.12.28</a>
            <a href="mailto:admin@codetoadventure.com" class="footer-link" target='_blank' rel='noopener noreferrer'>E-mail the admin</a>
        </div>
    </footer>

    <!-- Modal Dialog -->
        <div class="modal" id="submitModal">
        <div class="form-container">
            <!-- <button class="modal-close" onclick="closeModal()">&times;</button> -->
            <h1>Submit Your Referral Code</h1>
            <form action="store_code.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="referralCode">Referral Code<br>(Just the code - ex. ZAK1452284)</label>
                <input type="text" id="referralCode" name="referralCode" required>
                <br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Add JavaScript functions -->
    <script>
        function showModal() {
            document.getElementById('submitModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('submitModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('submitModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>