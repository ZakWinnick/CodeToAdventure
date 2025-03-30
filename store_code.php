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

// Test endpoint
if (isset($_GET['test_twitter'])) {
    header('Content-Type: text/plain');
    echo "=== Starting Twitter API Test ===\n\n";
    
    // Get pending tweets count
    $pendingQuery = "SELECT COUNT(*) as count FROM pending_tweets WHERE tweeted = 0";
    $result = $conn->query($pendingQuery);
    $row = $result->fetch_assoc();
    
    echo "Pending tweets: " . $row['count'] . "\n\n";
    
    // Get today's tweet stats
    $today = date('Y-m-d');
    $statsQuery = "SELECT * FROM tweet_stats WHERE date = '$today'";
    $result = $conn->query($statsQuery);
    $stats = $result->fetch_assoc();
    
    if ($stats) {
        echo "Tweets sent today: " . $stats['tweets_sent'] . "/17\n";
        echo "Last tweet time: " . $stats['last_tweet_time'] . "\n\n";
    } else {
        echo "No tweets sent today\n\n";
    }
    
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $referralCode = isset($_POST['referralCode']) ? trim(strtoupper($_POST['referralCode'])) : '';

        // Log the received values for debugging
        error_log("Received submission - Name: $name, Code: $referralCode");

        if (empty($name) || empty($referralCode)) {
            throw new Exception('Name and referral code are required');
        }

        if (!preg_match('/^(?=(?:.*[A-Za-z]){2})(?=(?:.*\d){7,})[A-Za-z0-9]+$/', $referralCode)) {
            throw new Exception('Invalid referral code format. The code must have at least 2 letters and at least 7 numbers.');
        }

        // Start transaction to prevent race conditions
        $conn->begin_transaction();

        try {
            // Check if code exists - using more precise query
            $checkSql = "SELECT referral_code FROM codes WHERE UPPER(referral_code) = ? LIMIT 1";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param('s', $referralCode);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            if ($result->num_rows > 0) {
                $existing = $result->fetch_assoc();
                error_log("Duplicate code detected: {$existing['referral_code']} vs submitted: $referralCode");
                $conn->rollback();
                throw new Exception('This referral code already exists in our system');
            }

            // Insert new code
            $sql = "INSERT INTO codes (name, referral_code) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $name, $referralCode);

            if ($stmt->execute()) {
                // Get the inserted code's ID
                $code_id = $conn->insert_id;
                
                // Add to pending tweets
                $pendingSql = "INSERT INTO pending_tweets (code_id, name, referral_code) VALUES (?, ?, ?)";
                $pendingStmt = $conn->prepare($pendingSql);
                $pendingStmt->bind_param('iss', $code_id, $name, $referralCode);
                $pendingStmt->execute();
                
                // Commit the transaction
                $conn->commit();
                error_log("Successfully inserted code: $referralCode for $name");
                
                // Get position in queue
                $queueQuery = "SELECT COUNT(*) as position FROM pending_tweets WHERE tweeted = 0 AND id <= LAST_INSERT_ID()";
                $queueResult = $conn->query($queueQuery);
                $queuePosition = $queueResult->fetch_assoc()['position'];
                
                // Calculate approximate time until tweet
                $tweetsAhead = floor(($queuePosition - 1) / 3); // 3 codes per tweet
                $hoursUntilTweet = $tweetsAhead * 4; // tweets every 4 hours
                
                // Send email notification
                $to = 'zak@codetoadventure.com';
                $subject = 'New Referral Code Submitted';
                $message = "A new referral code has been submitted:\n\n";
                $message .= "Name: $name\n\n";
                $message .= "Referral Code: $referralCode\n\n";
                $message .= "Queue Position: $queuePosition\n";
                $headers = "From: noreply@codetoadventure.com";

                mail($to, $subject, $message, $headers);

                echo json_encode([
                    'success' => true,
                    'message' => 'Code submitted successfully! It will be shared on X in our next update.'
                ]);
            } else {
                $conn->rollback();
                error_log("Database error when inserting code: " . $conn->error);
                throw new Exception('Error saving the code: ' . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Exception during code submission: " . $e->getMessage());
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