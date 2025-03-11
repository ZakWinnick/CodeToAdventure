<?php
require_once 'config.php'; // Ensure database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body class="!bg-white dark:!bg-gray-900 transition-colors duration-200">


    <?php include 'includes/header.php'; ?>

    <main class="main-content max-w-7xl mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="hero-title text-4xl font-bold text-primary dark:text-accent mb-4 transition-colors duration-200">
                Buying a Rivian?
            </h1>
            <h2 class="hero-subtitle text-2xl text-gray-700 dark:text-gray-300 transition-colors duration-200">
                Use a referral code and get rewards!
            </h2>
        </div>

        <?php
        // Restore original code to fetch a random referral code
        $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
        $referral = $result->fetch_assoc();
        ?>

        <?php if ($referral): ?>
            <!-- Referral Code Display Section -->
            <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transition-colors duration-200">
                <!-- Referral Button -->
                <a href="track.php?code=<?php echo htmlspecialchars($referral['referral_code']); ?>" 
                   class="block w-full text-center bg-primary hover:bg-secondary dark:bg-accent dark:hover:bg-secondary text-white font-semibold py-3 px-6 rounded-lg mb-4 transition-colors duration-200" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    Use <?php echo htmlspecialchars($referral['name']); ?>'s Code
                </a>

                <!-- Code Display -->
                <div class="code-container text-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg mb-4 transition-colors duration-200">
                    <span class="referral-code text-lg font-mono font-semibold text-gray-900 dark:text-gray-100">
                        <?php echo htmlspecialchars($referral['referral_code']); ?>
                    </span>
                    
                    <button 
                        onclick="copyCode('<?php echo htmlspecialchars($referral['referral_code']); ?>')" 
                        class="ml-4 inline-flex items-center px-3 py-1 bg-primary hover:bg-secondary dark:bg-accent dark:hover:bg-secondary text-white rounded-md transition-colors duration-200"
                        title="Copy code">
                        <span>⧉</span> Copy Code
                    </button>
                </div>

                <!-- Refresh Button -->
                <button 
                    onclick="getNewCode()" 
                    class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Get Another Code
                </button>

                <p class="refresh-text mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                    You'll be directed to Rivian's R1 Shop. Code changes every page refresh.
                </p>
            </div>
        <?php endif; ?>

        <!-- Information Section -->
        <div class="info-section max-w-4xl mx-auto mt-12 space-y-8">
            <!-- How it Works -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
            <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">
                    How does it work?
                </h3>
                <p class="text-white dark:text-gray-300">
                    When you use an owner's referral code during checkout of a qualifying R1 Shop vehicle, 
                    then take delivery – both the original owner (referrer) and new owner (referee) get rewards!
                </p>
            </div>

            <!-- Rewards Section -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
            <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">
                    What are the rewards?
                </h3>
                
                <!-- Points Reward -->
<div class="mb-6">
    <p class="text-white dark:text-gray-100">
        500 points that can be redeemed in Gear Shop or R1 Shop
    </p>
    <p class="text-gray-300 dark:text-gray-400 text-sm mt-1">
        (1 point equals 1 dollar in credit)
    </p>
</div>  

               <!-- Charging Reward
<div>
    <p class="text-white dark:text-gray-300">
        6 months of free charging at Rivian Adventure Network sites
    </p>
    <p class="text-gray-300 dark:text-gray-400 text-sm mt-1">
        (up to a lifetime limit of 3 years)
    </p>
</div> -->
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/modal.php'; ?>
    
    <script src="js/main.js"></script>
</body>
</html>
