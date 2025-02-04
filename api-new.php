<?php
include 'config.php';

// Security headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://codetoadventure.com'); // Restrict to specific domain
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// HTTPS enforcement
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Input validation function
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Rate limiting
session_start();
if (!isset($_SESSION['last_api_call'])) {
    $_SESSION['last_api_call'] = [];
}

$ip = $_SERVER['REMOTE_ADDR'];
$time = time();
$_SESSION['last_api_call'][$ip] = isset($_SESSION['last_api_call'][$ip]) ? $_SESSION['last_api_call'][$ip] : [];
$_SESSION['last_api_call'][$ip][] = $time;

// Remove old entries
$_SESSION['last_api_call'][$ip] = array_filter($_SESSION['last_api_call'][$ip], function($timestamp) use ($time) {
    return ($time - $timestamp) < 60;
});

// Check rate limit
$remaining = 100 - count($_SESSION['last_api_call'][$ip]);
header('X-RateLimit-Limit: 100');
header('X-RateLimit-Remaining: ' . $remaining);
header('X-RateLimit-Reset: ' . ($time + 60));

if (count($_SESSION['last_api_call'][$ip]) > 100) {
    http_response_code(429);
    echo json_encode([
        'error' => [
            'code' => 'RATE_LIMIT_EXCEEDED',
            'message' => 'Rate limit exceeded. Try again later.'
        ]
    ]);
    exit();
}

// Get and validate path
$path = isset($_GET['path']) ? validateInput($_GET['path']) : '';

// API endpoint: /api/random
if ($path === 'random') {
    $stmt = $conn->prepare("SELECT * FROM codes ORDER BY RAND() LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $referral = $result->fetch_assoc();
        $response = [
            'name' => $referral['name'],
            'username' => $referral['username'],
            'referral_code' => $referral['referral_code'],
            'link' => 'https://rivian.com/configurations/list?reprCode=' . $referral['referral_code']
        ];
        
        // Set cache headers
        header('Cache-Control: public, max-age=300');
        header('ETag: ' . md5(json_encode($response)));
        
        echo json_encode($response);
    } else {
        http_response_code(404);
        echo json_encode([
            'error' => [
                'code' => 'NO_CODES_FOUND',
                'message' => 'No referral codes available'
            ]
        ]);
    }
    exit();
}

// API endpoint: /api/codes
if ($path === 'codes') {
    $stmt = $conn->prepare("SELECT * FROM codes");
    $stmt->execute();
    $result = $stmt->get_result();
    $codes = [];
    
    while ($row = $result->fetch_assoc()) {
        $codes[] = [
            'name' => $row['name'],
            'username' => $row['username'],
            'referral_code' => $row['referral_code'],
            'link' => 'https://rivian.com/configurations/list?reprCode=' . $row['referral_code']
        ];
    }
    
    // Set cache headers
    header('Cache-Control: public, max-age=300');
    header('ETag: ' . md5(json_encode($codes)));
    
    echo json_encode($codes);
    exit();
}

// Invalid endpoint
http_response_code(404);
echo json_encode([
    'error' => [
        'code' => 'INVALID_ENDPOINT',
        'message' => 'Invalid API endpoint'
    ]
]);
$conn->close();
?>
