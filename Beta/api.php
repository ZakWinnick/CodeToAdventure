<?php
include 'config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Allow all domains, can be restricted to specific domains

// Simple rate limiting (100 requests per minute per IP)
session_start();
if (!isset($_SESSION['last_api_call'])) {
    $_SESSION['last_api_call'] = [];
}

$ip = $_SERVER['REMOTE_ADDR'];
$time = time();
$_SESSION['last_api_call'][$ip] = isset($_SESSION['last_api_call'][$ip]) ? $_SESSION['last_api_call'][$ip] : [];
$_SESSION['last_api_call'][$ip][] = $time;

// Remove old entries older than 60 seconds
$_SESSION['last_api_call'][$ip] = array_filter($_SESSION['last_api_call'][$ip], function($timestamp) use ($time) {
    return ($time - $timestamp) < 60;
});

// Check rate limit
if (count($_SESSION['last_api_call'][$ip]) > 100) {
    http_response_code(429); // Too many requests
    echo json_encode(['error' => 'Rate limit exceeded. Try again later.']);
    exit();
}

// Get the path from the URL
$path = isset($_GET['path']) ? $_GET['path'] : '';

// API endpoint: /api/random
if ($path === 'random') {
    // Fetch a random referral code
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $referral = $result->fetch_assoc();
        echo json_encode([
            'name' => $referral['name'],
            'username' => $referral['username'],
            'referral_code' => $referral['referral_code'],
            'link' => 'https://rivian.com/configurations/list?reprCode=' . $referral['referral_code']
        ]);
    } else {
        echo json_encode(['error' => 'No referral codes available']);
    }
    exit();
}

// API endpoint: /api/codes
if ($path === 'codes') {
    // Fetch all referral codes
    $sql = "SELECT * FROM codes";
    $result = $conn->query($sql);
    $codes = [];
    while ($row = $result->fetch_assoc()) {
        $codes[] = [
            'name' => $row['name'],
            'username' => $row['username'],
            'referral_code' => $row['referral_code'],
            'link' => 'https://rivian.com/configurations/list?reprCode=' . $row['referral_code']
        ];
    }
    echo json_encode($codes);
    exit();
}

// If the path doesn't match any known API endpoints, return a 404 response
http_response_code(404);
echo json_encode(['error' => 'Invalid API endpoint']);
$conn->close();
?>
