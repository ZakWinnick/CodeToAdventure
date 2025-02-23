<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check user's country via IPQualityScore
function getUserCountry($ip) {
    $apiKey = "eNSs5rqnjmbTQlR0fgh0CA4s2QpQA2Ez"; // Your IPQualityScore API key
    $url = "https://www.ipqualityscore.com/api/json/ip/{$apiKey}/{$ip}";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Log access attempts for debugging
    file_put_contents("/home/zakwinnick/codetoadventure.com/access_log.log", 
        date("Y-m-d H:i:s") . " - IP: {$ip} - Country: " . ($data['country_code'] ?? "Unknown") . "\n", 
        FILE_APPEND);

    return $data['country_code'] ?? null;
}

// Get the user's IP address
$user_ip = $_SERVER['REMOTE_ADDR'];
$allowed_countries = ["US", "CA"];
$user_country = getUserCountry($user_ip);

// Restrict access if the user is not from the US or Canada
if (!in_array($user_country, $allowed_countries)) {
    http_response_code(403);
    echo "<h1>Access Denied</h1><p>Sorry, this website is only available in the US and Canada.</p>";
    exit;
}
?>
