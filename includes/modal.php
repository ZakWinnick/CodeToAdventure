<?php
// Ensure session is only started if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

header("Content-Type: application/json");

function getUserLocation($ip) {
    $apiKey = "eNSs5rqnjmbTQlR0fgh0CA4s2QpQA2Ez"; // Your IPQualityScore API key
    $url = "https://www.ipqualityscore.com/api/json/ip/{$apiKey}/{$ip}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Check for VPN, Proxy, or Tor usage
    $isVPN = isset($data['vpn']) ? $data['vpn'] : false;
    $isProxy = isset($data['proxy']) ? $data['proxy'] : false;
    $isTor = isset($data['tor']) ? $data['tor'] : false;
    $fraudScore = isset($data['fraud_score']) ? $data['fraud_score'] : 0;

    // Set a fraud score threshold to block bad IPs
    $highRisk = $fraudScore > 75; 

    return [
        'country' => $data['country_code'] ?? null,
        'blocked' => ($isVPN || $isProxy || $isTor || $highRisk)
    ];
}

$allowed_countries = ["US", "CA"];
$user_ip = $_SERVER['REMOTE_ADDR'];
$user_data = getUserLocation($user_ip);

// Block VPN, proxies, Tor users, and high-risk IPs
if ($user_data['blocked']) {
    file_put_contents("vpn_attempts.log", date("Y-m-d H:i:s") . " - BLOCKED: {$user_ip} - Country: {$user_data['country']}\n", FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Access restricted due to VPN, proxy, or high-risk activity."]);
    exit;
}

// Block users outside US & Canada
if (!in_array($user_data['country'], $allowed_countries)) {
    echo json_encode(["success" => false, "message" => "Submissions are only allowed from the US and Canada."]);
    exit;
}

// Ensure JavaScript verification
if (empty($_POST['js_verified']) || $_POST['js_verified'] !== 'true') {
    echo json_encode(["success" => false, "message" => "JavaScript verification failed."]);
    exit;
}

// Process the referral code submission
$name = trim($_POST['name'] ?? '');
$referralCode = trim($_POST['referralCode'] ?? '');

if (empty($name) || empty($referralCode)) {
    echo json_encode(["success" => false, "message" => "Name and referral code are required."]);
    exit;
}

// Here, insert the referral code into your database (ensure proper DB handling).

echo json_encode(["success" => true, "message" => "Referral code submitted successfully!"]);
?>

<!-- Existing Modal UI (Preserved) -->

<div id="submitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md w-full mx-4 relative">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Submit Your Referral Code</h2>
        
        <form onsubmit="handleFormSubmit(event)" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500">
            </div>

            <div>
                <label for="referralCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referral Code</label>
                <input type="text" id="referralCode" name="referralCode" placeholder="Ex: ZAK1452284" required
                    pattern="(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+"
                    title="Code must contain at least 2 letters and 7 numbers"
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-gray-900 dark:text-white placeholder-gray-500">
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-primary hover:bg-secondary text-white rounded-md">
                    Submit Code
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast notification for feedback -->
<div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg hidden"></div>