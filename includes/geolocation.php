<?php
// Enable error reporting for debugging (Remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure session is only started if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Prevent execution if included inside an HTML page
if (!defined('GEO_CHECK')) {
    return;
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

    // If API request fails, return null
    if ($http_status !== 200 || empty($response)) {
        error_log("Geolocation API request failed for IP: $ip");
        return null;
    }

    $data = json_decode($response, true);

    // Log access attempts for debugging
    $logFile = __DIR__ . "/access_log.log";
    file_put_contents($logFile, date("Y-m-d H:i:s") . " - IP: {$ip} - Country: " . ($data['country_code'] ?? "Unknown") . "\n", FILE_APPEND | LOCK_EX);

    return $data['country_code'] ?? null;
}

// Get the user's IP address
$user_ip = $_SERVER['REMOTE_ADDR'];
$allowed_countries = ["US", "CA"];
$user_country = getUserCountry($user_ip);

// Restrict access if the user's country could not be determined
if ($user_country === null) {
    http_response_code(500);
    error_log("500 Error: Unable to determine user's country for IP: $user_ip");
    echo "<h1>Service Unavailable</h1><p>We could not verify your location at this time. Please try again later.</p>";
    exit;
}

// Restrict access if the user is not from the US or Canada
if (!in_array($user_country, $allowed_countries)) {
    http_response_code(403);
    error_log("403 Error: User from {$user_country} blocked. IP: $user_ip");
    echo "<h1>Access Denied</h1><p>Sorry, this website is only available in the US and Canada.</p>";
    exit;
}
?>
