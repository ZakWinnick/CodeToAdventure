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

function postToTwitter($name, $referralCode) {
    try {
        // Use v1.1 endpoint instead of v2
        $url = 'https://api.twitter.com/1.1/statuses/update.json';
        
        // Craft the tweet message
        $rivianShopUrl = "https://rivian.com/configurations/list?reprCode=" . urlencode($referralCode);
        $tweetText = "New Rivian referral code available!\n\n";
        $tweetText .= "Use {$name}'s code for \$500 in credit + 6 months free charging!\n\n";
        $tweetText .= $rivianShopUrl . "\n\n";
        $tweetText .= "#Rivian #R1T #R1S";
        
        $oauth_nonce = time();
        $oauth_timestamp = time();
        
        $oauth_params = [
            'oauth_consumer_key' => TWITTER_API_KEY,
            'oauth_nonce' => $oauth_nonce,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $oauth_timestamp,
            'oauth_token' => TWITTER_ACCESS_TOKEN,
            'oauth_version' => '1.0',
            'status' => $tweetText // Include status in signature for v1.1 API
        ];
        
        // Generate signature
        $signature = generate_oauth_signature(
            'POST',
            $url,
            $oauth_params,
            TWITTER_ACCESS_TOKEN_SECRET
        );
        
        // Remove status from oauth_params for header
        $header_params = $oauth_params;
        unset($header_params['status']);
        $header_params['oauth_signature'] = $signature;
        
        // Create Authorization header
        $auth_header = 'OAuth ';
        $header_parts = [];
        foreach ($header_params as $key => $value) {
            $header_parts[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
        }
        $auth_header .= implode(', ', $header_parts);
        
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['status' => $tweetText],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $auth_header,
                'Content-Type: application/x-www-form-urlencoded',
                'Expect:'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        
        // v1.1 API returns 200 for success and includes 'id_str' in response
        return $httpCode === 200 && isset($responseData['id_str']);
    } catch (Exception $e) {
        return false;
    }
}

// Test endpoint
if (isset($_GET['test_twitter'])) {
    header('Content-Type: text/plain');
    echo "=== Starting Twitter API Test ===\n\n";
    
    echo "Testing Tweet Post\n";
    $result = postToTwitter('Test User', 'TEST1234567');
    
    if ($result) {
        echo "Success: Tweet was posted!\n";
    } else {
        echo "Failed: Check error logs for details\n";
    }
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $referralCode = isset($_POST['referralCode']) ? trim($_POST['referralCode']) : '';

        if (empty($name) || empty($referralCode)) {
            throw new Exception('Name and referral code are required');
        }

        if (!preg_match('/^(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+$/', $referralCode)) {
            throw new Exception('Invalid referral code format. The code must have at least 2 letters and at least 7 numbers.');
        }

        // Start transaction to prevent race conditions
        $conn->begin_transaction();

        try {
            // Check if code exists
            $checkSql = "SELECT COUNT(*) as count FROM codes WHERE referral_code = ? FOR UPDATE";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param('s', $referralCode);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                $conn->rollback();
                throw new Exception('This referral code already exists');
            }

            error_log("Attempting to post tweet for: " . $name . " with code: " . $referralCode);

            // Insert new code
            $sql = "INSERT INTO codes (name, referral_code) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $name, $referralCode);

            if ($stmt->execute()) {
                // Commit the transaction
                $conn->commit();
                
                // Post to Twitter
                $tweetSuccess = postToTwitter($name, $referralCode);
                
                // Send email
                $to = 'zak@codetoadventure.com';
                $subject = 'New Referral Code Submitted';
                $message = "A new referral code has been submitted:\n\n";
                $message .= "Name: $name\n\n";
                $message .= "Referral Code: $referralCode\n\n";
                $message .= "Twitter Post Status: " . ($tweetSuccess ? "Success" : "Failed");
                $headers = "From: noreply@codetoadventure.com";

                mail($to, $subject, $message, $headers);

                echo json_encode([
                    'success' => true,
                    'message' => 'Code submitted successfully!' . ($tweetSuccess ? '' : ' (Twitter post pending)')
                ]);
            } else {
                $conn->rollback();
                throw new Exception('Error saving the code');
            }
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// If no valid request method, return error
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Invalid request method'
]);