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
            <h1 class="text-4xl font-bold text-primary dark:text-accent mb-4 transition-colors duration-200">
                API Documentation
            </h1>
            <h2 class="text-2xl text-gray-700 dark:text-gray-300 transition-colors duration-200">
                Access the Code to Adventure API to retrieve referral codes.
            </h2>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Overview -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Overview</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    The Code to Adventure API provides programmatic access to referral codes. 
                    All requests must use HTTPS.
                </p>
                <p class="text-gray-700 dark:text-gray-300 mt-4">
                    <strong>Base URL:</strong> 
                    <code class="bg-gray-200 dark:bg-gray-900 px-2 py-1 rounded">https://codetoadventure.com/api.php</code>
                </p>
            </div>

            <!-- Authentication -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Authentication</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    Currently, the API uses IP-based rate limiting and does not require authentication.
                </p>
            </div>

            <!-- Endpoints -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Endpoints</h3>

                <!-- Fetch Random Referral Code -->
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-gray-100 font-semibold">1. Fetch Random Referral Code</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">GET /api.php?path=random</p>

                    <p class="text-gray-900 dark:text-gray-300 font-semibold mt-4">Example Response:</p>
                    <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-green-400 p-4 rounded-lg overflow-x-auto text-sm">
{
    "name": "John Doe",
    "username": "johndoe123",
    "referral_code": "ABC123",
    "link": "https://rivian.com/configurations/list?reprCode=ABC123"
}</pre>
                </div>

                <!-- Fetch All Referral Codes -->
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-gray-100 font-semibold">2. Fetch All Referral Codes</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">GET /api.php?path=codes</p>

                    <p class="text-gray-900 dark:text-gray-300 font-semibold mt-4">Example Response:</p>
                    <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-green-400 p-4 rounded-lg overflow-x-auto text-sm">
[
    {
        "name": "John Doe",
        "username": "johndoe123",
        "referral_code": "ABC123",
        "link": "https://rivian.com/configurations/list?reprCode=ABC123"
    }
]</pre>
                </div>
            </div>

            <!-- Rate Limiting -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Rate Limiting</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    Limits: 100 requests per minute per IP address.
                </p>

                <p class="text-gray-900 dark:text-gray-300 font-semibold mt-4">Response Headers:</p>
                <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-green-400 p-4 rounded-lg overflow-x-auto text-sm">
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1635789600</pre>
            </div>

            <!-- Error Responses -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Error Responses</h3>
                <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-red-400 p-4 rounded-lg overflow-x-auto text-sm">
{
    "error": {
        "code": "ERROR_CODE",
        "message": "Human readable message"
    }
}</pre>
            </div>

            <!-- HTTP Status Codes -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">HTTP Status Codes</h3>
                <ul class="list-disc list-inside text-gray-900 dark:text-gray-300 mt-4">
                    <li><strong>200</strong> - Success</li>
                    <li><strong>404</strong> - Not Found</li>
                    <li><strong>429</strong> - Rate Limit Exceeded</li>
                    <li><strong>500</strong> - Server Error</li>
                </ul>
            </div>

            <!-- Caching -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Caching</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    Responses are cached for 5 minutes. Use the ETag header for conditional requests.
                </p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
