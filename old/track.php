<?php
require_once 'config.php';

// Get the referral code
$code = isset($_GET['code']) ? $_GET['code'] : '';

if (!empty($code)) {
    // Get user info
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    // Check if this IP has used this code in the last 24 hours
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM code_analytics 
        WHERE referral_code = ? AND ip_address = ? 
        AND timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stmt->bind_param("ss", $code, $ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $is_unique = ($row['count'] == 0);

    // Get geolocation data using IP-API (free tier)
    $geo_data = @file_get_contents("http://ip-api.com/json/{$ip}");
    $geo = json_decode($geo_data, true);
    $country = isset($geo['countryCode']) ? $geo['countryCode'] : '';
    $city = isset($geo['city']) ? $geo['city'] : '';

    // Record analytics
    $stmt = $conn->prepare("INSERT INTO code_analytics 
        (referral_code, ip_address, user_agent, country, city, is_unique) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $code, $ip, $user_agent, $country, $city, $is_unique);
    $stmt->execute();

    // Only update use_count if it's a unique visit
    if ($is_unique) {
        $stmt = $conn->prepare("UPDATE codes SET 
            use_count = use_count + 1,
            last_used = NOW()
            WHERE referral_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
    }
    
    // Redirect to Rivian with the code
    header("Location: https://rivian.com/configurations/list?reprCode=" . urlencode($code));
    exit;
} else {
    // If no code provided, redirect to home page
    header("Location: index.php");
    exit;
}