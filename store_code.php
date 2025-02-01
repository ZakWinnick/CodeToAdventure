<?php
include 'config.php';

// Set response headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

// Load credentials from an external file
$creds = require __DIR__ . '/credentials.php';

define('TWITTER_API_KEY', $creds['TWITTER_API_KEY']);
define('TWITTER_API_SECRET', $creds['TWITTER_API_SECRET']);
define('TWITTER_ACCESS_TOKEN', $creds['TWITTER_ACCESS_TOKEN']);
define('TWITTER_ACCESS_TOKEN_SECRET', $creds['TWITTER_ACCESS_TOKEN_SECRET']);


/**
 * Generate an OAuth 1.0a signature.
 *
 * @param string $method HTTP method (GET, POST, etc.)
 * @param string $url    Request URL.
 * @param array  $params Array of parameters to be included in the signature.
 * @param string $oauth_token_secret The OAuth token secret.
 * @return string The generated signature.
 */
function generate_oauth_signature($method, $url, $params, $oauth_token_secret) {
    $parts = [];
    ksort($params);
    
    foreach ($params as $key => $value) {
        $parts[] = rawurlencode($key) . '=' . rawurlencode($value);
    }
    
    $base_string = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $parts));
    $signing_key = rawurlencode(TWITTER_API_SECRET) . '&' . rawurlencode($oauth_token_secret);
    
    return base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));
}

/**
 * Post a tweet using Twitter API v2.
 *
 * Since we are sending a JSON payload (instead of URL-encoded form data),
 * the tweet text is NOT included in the OAuth signature base string.
 *
 * @param string $name The name to include in the tweet.
 * @param string $referralCode The referral code to include.
 * @return bool True if tweet posting was successful.
 */
function postToTwitter($name, $referralCode) {
    try {
        error_log("=== Twitter Post Attempt Start ===");
        error_log("Name: " . $name);
        error_log("Referral Code: " . $referralCode);
        
        // Use the v2 endpoint
        $url = 'https://api.twitter.com/2/tweets';
        
        // Craft the tweet message without the emoji
        $rivianShopUrl = "https://rivian.com/configurations/list?reprCode=" . urlencode($referralCode);
        $tweetText = "New Rivian referral code available!\n\n";
        $tweetText .= "Use {$name}'s code for \$500 in credit + 6 months free charging!\n\n";
        $tweetText .= $rivianShopUrl . "\n\n";
        $tweetText .= "#Rivian #R1T #R1S";
        
        // Generate OAuth parameters (body parameters are NOT included for JSON)
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        
        $oauth_params = [
            'oauth_consumer_key'     => TWITTER_API_KEY,
            'oauth_nonce'            => $oauth_nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => $oauth_timestamp,
            'oauth_token'            => TWITTER_ACCESS_TOKEN,
            'oauth_version'          => '1.0'
        ];
        
        // Generate the signature based solely on the OAuth parameters
        $signature = generate_oauth_signature(
            'POST',
            $url,
            $oauth_params,
            TWITTER_ACCESS_TOKEN_SECRET
        );
        
        $oauth_params['oauth_signature'] = $signature;
        
        // Create Authorization header (include only the oauth_* parameters)
        $auth_header = 'OAuth ';
        $header_parts = [];
        foreach ($oauth_params as $key => $value) {
            $header_parts[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
        }
        $auth_header .= implode(', ', $header_parts);
        
        error_log("Authorization Header: " . $auth_header);
        
        // Prepare the JSON POST data
        $postData = json_encode(['text' => $tweetText]);
        error_log("POST Data: " . $postData);
        
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $auth_header,
                'Content-Type: application/json',
                'User-Agent: CodeToAdventure/1.0'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_VERBOSE        => true
        ]);
        
        // For debugging purposes
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        error_log("HTTP Response Code: " . $httpCode);
        error_log("Raw Response: " . $response);
        
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("Verbose curl output: " . $verboseLog);
        
        if (curl_errno($ch)) {
            error_log("cURL Error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        error_log("Tweet response: " . print_r($responseData, true));
        
        // Twitter v2 returns 201 for successful tweet creation.
        $success = ($httpCode === 201 || $httpCode === 200) && isset($responseData['data']['id']);
        error_log("Tweet post success: " . ($success ? "true" : "false"));
        return $success;
    } catch (Exception $e) {
        error_log("Twitter posting error: " . $e->getMessage());
        error_log("Error trace: " . $e->getTraceAsString());
        return false;
    }
}

// Test endpoint with detailed output (for debugging purposes)
if (isset($_GET['test_twitter'])) {
    header('Content-Type: text/plain');
    echo "=== Starting Twitter API Test ===\n\n";
    
    echo "Testing Tweet Post\n";
    echo "Using API Key: " . substr(TWITTER_API_KEY, 0, 8) . "...\n";
    echo "Using Access Token: " . substr(TWITTER_ACCESS_TOKEN, 0, 8) . "...\n\n";
    
    // Use the v2 endpoint for testing
    $url = 'https://api.twitter.com/2/tweets';
    
    // Craft a test tweet without emoji
    $tweetText = "Test tweet from Code To Adventure " . date('Y-m-d H:i:s');
    
    // Generate OAuth parameters (again, do NOT include body parameters in the signature)
    $oauth_nonce = md5(uniqid(rand(), true));
    $oauth_timestamp = time();
    
    $oauth_params = [
        'oauth_consumer_key'     => TWITTER_API_KEY,
        'oauth_nonce'            => $oauth_nonce,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp'        => $oauth_timestamp,
        'oauth_token'            => TWITTER_ACCESS_TOKEN,
        'oauth_version'          => '1.0'
    ];
    
    // Generate the signature without including the tweet text
    $signature = generate_oauth_signature(
        'POST',
        $url,
        $oauth_params,
        TWITTER_ACCESS_TOKEN_SECRET
    );
    
    $oauth_params['oauth_signature'] = $signature;
    
    // Create Authorization header
    $auth_header = 'OAuth ';
    $header_parts = [];
    foreach ($oauth_params as $key => $value) {
        $header_parts[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
    }
    $auth_header .= implode(', ', $header_parts);
    
    echo "Authorization Header:\n" . $auth_header . "\n\n";
    
    // Build JSON POST data
    $postData = json_encode(['text' => $tweetText]);
    echo "Post Data:\n" . $postData . "\n\n";
    
    // Initialize cURL session for test tweet
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_HTTPHEADER     => [
            'Authorization: ' . $auth_header,
            'Content-Type: application/json',
            'User-Agent: CodeToAdventure/1.0'
        ],
        CURLOPT_VERBOSE        => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);
    
    // Capture verbose output
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "HTTP Code: " . $httpCode . "\n";
    echo "Response: " . $response . "\n\n";
    
    // Get verbose information
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    echo "Verbose Log:\n" . $verboseLog . "\n";
    
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
    }
    
    curl_close($ch);
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== Form Submission Start ===");
    try {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $referralCode = isset($_POST['referralCode']) ? trim($_POST['referralCode']) : '';
        
        error_log("Form data - Name: " . $name . ", Code: " . $referralCode);

        if (empty($name) || empty($referralCode)) {
            throw new Exception('Name and referral code are required');
        }

        if (!preg_match('/^(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+$/', $referralCode)) {
            throw new Exception('Invalid referral code format. The code must have at least 2 letters and at least 7 numbers.');
        }

        // Check if the code already exists
        $checkSql = "SELECT COUNT(*) as count FROM codes WHERE referral_code = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('s', $referralCode);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            throw new Exception('This referral code already exists');
        }

        // Insert new code into the database
        $sql = "INSERT INTO codes (name, referral_code) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $name, $referralCode);

        if ($stmt->execute()) {
            // Post the tweet
            $tweetSuccess = postToTwitter($name, $referralCode);
            
            // Send an email notification
            $to = 'zak@codetoadventure.com';
            $subject = 'New Referral Code Submitted';
            $message = "A new referral code has been submitted:\n\n";
            $message .= "Name: $name\n\n";
            $message .= "Referral Code: $referralCode\n\n";
            $message .= "Twitter Post Status: " . ($tweetSuccess ? "Success" : "Failed");
            $headers = "From: noreply@codetoadventure.com";

            mail($to, $subject, $message, $headers);
            error_log("Email notification sent");

            echo json_encode([
                'success' => true,
                'message' => 'Code submitted successfully!'
            ]);
        } else {
            throw new Exception('Error saving the code');
        }

    } catch (Exception $e) {
        error_log("Form submission error: " . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    } finally {
        error_log("=== Form Submission End ===");
        if (isset($conn)) {
            $conn->close();
        }
    }
    exit;
}

// If no valid request method, return error
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Invalid request method'
]);
