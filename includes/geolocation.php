<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check user's country via IPQualityScore (with fallback)
function getUserCountry($ip) {
    $apiKey = "eNSs5rqnjmbTQlR0fgh0CA4s2QpQA2Ez"; // Your IPQualityScore API key
    $url = "https://www.ipqualityscore.com/api/json/ip/{$apiKey}/{$ip}";

    // First try using file_get_contents()
    $response = @file_get_contents($url);

    // If file_get_contents() fails, use cURL as a fallback
    if ($response === false || empty($response)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    // If both methods fail, return null
    if (empty($response)) {
        return null;
    }

    $data = json_decode($response, true);

    // Log access attempts for debugging
    $logFile = __DIR__ . "/access_log.log";

    // Ensure the log file exists and is writable
    if (!file_exists($logFile)) {
        touch($logFile);
        chmod($logFile, 0666); // Set write permissions
    }

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
    echo "<h1>Service Unavailable</h1><p>We could not verify your location at this time. Please try again later.</p>";
    exit;
}

// Restrict access if the user is not from the US or Canada
if (!in_array($user_country, $allowed_countries)) {
    http_response_code(403);
    echo "<h1>Access Denied</h1><p>Sorry, this website is only available in the US and Canada.</p>";
    exit;
}
?>
