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

        <!-- API Documentation Content -->
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Getting Started -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Getting Started</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    The API allows you to fetch Rivian referral codes programmatically. Use the endpoints below to integrate with your app.
                </p>
            </div>

            <!-- API Endpoints -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">API Endpoints</h3>

                <!-- Fetch Random Code -->
                <div class="mb-6">
                    <p class="text-gray-900 dark:text-gray-100 font-semibold">Fetch a Random Referral Code</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">GET /get_new_code.php</p>
                </div>

                <!-- Example Request -->
                <p class="text-gray-900 dark:text-gray-300 font-semibold">Example Request:</p>
                <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-green-400 p-4 rounded-lg overflow-x-auto text-sm">
curl -X GET "https://codetoadventure.com/get_new_code.php"
                </pre>

                <!-- Response Example -->
                <p class="text-gray-900 dark:text-gray-300 font-semibold mt-4">Example Response:</p>
                <pre class="bg-gray-200 text-gray-800 dark:bg-gray-900 dark:text-green-400 p-4 rounded-lg overflow-x-auto text-sm">
{
    "success": true,
    "code": {
        "name": "John Doe",
        "referral_code": "JOHN1234567"
    }
}
                </pre>
            </div>

            <!-- Request Parameters -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Request Parameters</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    The following parameters can be used to customize your request.
                </p>
                <ul class="list-disc list-inside text-gray-900 dark:text-gray-300 mt-4">
                    <li><span class="font-semibold">current</span> (optional) - Excludes the current referral code from results.</li>
                </ul>
            </div>

            <!-- Response Codes -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Response Codes</h3>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-green-600 dark:text-green-400">200</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Success - Returns a valid referral code</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-yellow-600 dark:text-yellow-400">400</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Bad Request - Missing or invalid parameters</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-red-600 dark:text-red-400">500</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Internal Server Error - Try again later</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl p-6 shadow-xl transition-colors duration-200">
                <h3 class="text-xl font-bold text-amber-600 dark:text-amber-400 mb-4">Notes</h3>
                <p class="text-gray-700 dark:text-gray-300">
                    This API is for personal and non-commercial use. Abuse of the service may result in rate limits or restrictions.
                </p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
