<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_log("Script started.");

include 'config.php';

// Check if TwitterOAuth files exist before requiring them
if (!file_exists(__DIR__ . '/twitteroauth/src/TwitterOAuth.php')) {
    error_log("ERROR: TwitterOAuth.php not found!");
    die("ERROR: TwitterOAuth.php not found!");
}

// Manually include TwitterOAuth and its dependencies
require 'twitteroauth/src/Config.php';
require 'twitteroauth/src/Consumer.php';
require 'twitteroauth/src/SignatureMethod.php';
require 'twitteroauth/src/HmacSha1.php';
require 'twitteroauth/src/Request.php';
require 'twitteroauth/src/Response.php';
require 'twitteroauth/src/Token.php';
require 'twitteroauth/src/TwitterOAuthException.php';
require 'twitteroauth/src/TwitterOAuth.php';
require 'twitteroauth/src/Util.php';
require 'twitteroauth/src/Util/JsonDecoder.php';

// Manually include CaBundle to fix missing class error
if (!file_exists(__DIR__ . '/twitteroauth/vendor/composer/ca-bundle/src/CaBundle.php')) {
    error_log("ERROR: CaBundle.php not found!");
    die("ERROR: CaBundle.php not found!");
}
require 'twitteroauth/vendor/composer/ca-bundle/src/CaBundle.php';

use Abraham\TwitterOAuth\TwitterOAuth;

// Verify database connection
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection failed: " . mysqli_connect_error());
}

// Load Twitter credentials
$creds = require __DIR__ . '/credentials.php';

define('TWITTER_API_KEY', $creds['TWITTER_API_KEY']);
define('TWITTER_API_SECRET', $creds['TWITTER_API_SECRET']);
define('TWITTER_ACCESS_TOKEN', $creds['TWITTER_ACCESS_TOKEN']);
define('TWITTER_ACCESS_TOKEN_SECRET', $creds['TWITTER_ACCESS_TOKEN_SECRET']);

echo "API Key: " . substr(TWITTER_API_KEY, 0, 5) . "...\n";
echo "Access Token: " . substr(TWITTER_ACCESS_TOKEN, 0, 5) . "...\n";

// Authenticate with Twitter
$connection = new TwitterOAuth(
    TWITTER_API_KEY,
    TWITTER_API_SECRET,
    TWITTER_ACCESS_TOKEN,
    TWITTER_ACCESS_TOKEN_SECRET
);

$connection->setApiVersion('2');

if (!$connection) {
    error_log("Failed to initialize TwitterOAuth.");
    die("Failed to initialize TwitterOAuth.");
}

// Verify credentials
$auth_response = $connection->get("users/me");
echo "Authentication Test Response: " . json_encode($auth_response) . "\n";
error_log("Auth response: " . json_encode($auth_response));

echo "Last HTTP Code: " . $connection->getLastHttpCode() . "\n";
error_log("Last HTTP Code: " . $connection->getLastHttpCode());

if (isset($auth_response->errors)) {
    error_log("Authentication error: " . json_encode($auth_response->errors));
    die("Authentication error: " . json_encode($auth_response->errors));
}

// Check rate limits
$headers = $connection->getLastXHeaders();
echo "Response Headers: " . json_encode($headers) . "\n";

if (isset($headers['x-user-limit-24hour-remaining'])) {
    $tweets_remaining = $headers['x-user-limit-24hour-remaining'];
    echo "Tweets remaining in 24-hour window: " . $tweets_remaining . "\n";
    if ($tweets_remaining <= 0) {
        echo "Daily tweet limit reached. Exiting.\n";
        exit;
    }
}

// Get pending tweets
$query = "SELECT * FROM pending_tweets WHERE tweeted = 0 ORDER BY submitted_at ASC LIMIT 3";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo "No pending tweets found.\n";
    exit;
}

$codes = [];
while ($row = $result->fetch_assoc()) {
    $codes[] = $row;
}

// Create tweet text
$timeOfDay = (int)date('H') < 12 ? 'Morning' : ((int)date('H') < 17 ? 'Afternoon' : 'Evening');
$tweetText = "🚙 New Rivian Referral Codes - {$timeOfDay} Update\n\n";
foreach ($codes as $index => $code) {
    $tweetText .= ($index + 1) . ". {$code['name']}: {$code['referral_code']}\n";
}
$tweetText .= "\n➡️ Visit: codetoadventure.com\n#Rivian #R1T #R1S";

// Post tweet
$post_response = $connection->post("tweets", ["text" => $tweetText]);
echo "Post Response: " . json_encode($post_response) . "\n";
error_log("Post Response: " . json_encode($post_response));

$httpCode = $connection->getLastHttpCode();
echo "Last HTTP Code: " . $httpCode . "\n";

if ($httpCode == 201 && isset($post_response->data->id)) {
    $tweet_id = $post_response->data->id;
    $batch_id = uniqid();
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update all codes in this batch
        foreach ($codes as $code) {
            $updateSql = "UPDATE pending_tweets SET 
                tweeted = 1, 
                tweet_id = ?, 
                batch_id = ?,
                last_attempt = NOW() 
                WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('ssi', $tweet_id, $batch_id, $code['id']);
            $updateStmt->execute();
        }
        
        // Update or insert daily stats
        $today = date('Y-m-d');
        $statsQuery = "INSERT INTO tweet_stats (date, tweets_sent, last_tweet_time) 
                      VALUES (?, 1, NOW())
                      ON DUPLICATE KEY UPDATE 
                      tweets_sent = tweets_sent + 1,
                      last_tweet_time = NOW()";
        $statsStmt = $conn->prepare($statsQuery);
        $statsStmt->bind_param('s', $today);
        $statsStmt->execute();
        
        // Commit transaction
        $conn->commit();
        echo "Successfully posted tweet ID: " . $tweet_id . "\n";
        error_log("Successfully posted tweet ID: " . $tweet_id);
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating database: " . $e->getMessage() . "\n";
        error_log("Error updating database: " . $e->getMessage());
    }
} else {
    if ($httpCode == 429) {
        echo "Rate limit reached. Try again later.\n";
        error_log("Rate limit reached.");
    } else {
        echo "Failed to post tweet. HTTP Code: " . $httpCode . "\n";
        error_log("Failed to post tweet. HTTP Code: " . $httpCode);
        error_log("Response: " . json_encode($post_response));
    }
}

echo "Processing complete.\n";
$conn->close();