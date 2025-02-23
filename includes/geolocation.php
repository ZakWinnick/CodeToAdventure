<?php
// Ensure this script is only run via direct access
header("Content-Type: application/json");

// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure session is only started if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check user's country via IPQualityScore API
function getUserCountry($ip) {
    $apiKey = "eNSs5rqnjmbTQlR0fgh0CA4s2QpQA2Ez"; // Your IPQualityScore API key
    $url = "https://www.ipqualityscore.com/api/json/ip/{$apiKey}/{$ip}";

    // Use cURL for better compatibility
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // If API request fails, return an error
    if ($http_status !== 200 || empty($response)) {
        echo json_encode(["success" => false, "message" => "Geolocation API request failed."]);
        exit;
    }

    $data = json_decode($response, true);

    return [
        'country' => $data['country_code'] ?? null,
        'blocked' => $data['vpn'] || $data['proxy'] || $data['tor'] || ($data['fraud_score'] > 90)
    ];
}

// Get the user's IP address
$user_ip = $_SERVER['REMOTE_ADDR'];
$allowed_countries = ["US", "CA"];
$user_data = getUserCountry($user_ip);

// Block VPN, proxies, Tor users, and high-risk IPs
if ($user_data['blocked']) {
    echo json_encode(["success" => false, "message" => "Access restricted due to VPN, proxy, or high-risk activity."]);
    exit;
}

// Block users outside US & Canada
if (!in_array($user_data['country'], $allowed_countries)) {
    echo json_encode(["success" => false, "message" => "Submissions are only allowed from the US and Canada."]);
    exit;
}

// Return success if the user is allowed
echo json_encode(["success" => true, "message" => "Access granted."]);
exit;
?>
