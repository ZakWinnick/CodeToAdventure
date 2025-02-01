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

// Manually include TwitterOAuth files in correct order
require 'twitteroauth/src/Config.php';
require 'twitteroauth/src/Consumer.php';
require 'twitteroauth/src/SignatureMethod.php'; // Must be included before HmacSha1
require 'twitteroauth/src/HmacSha1.php';
require 'twitteroauth/src/Request.php';
require 'twitteroauth/src/Response.php';
require 'twitteroauth/src/TwitterOAuthException.php';
require 'twitteroauth/src/TwitterOAuth.php';
require 'twitteroauth/src/Util.php';

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

// Authenticate using OAuth 1.0a
$connection = new TwitterOAuth(
    TWITTER_API_KEY,
    TWITTER_API_SECRET,
    TWITTER_ACCESS_TOKEN,
    TWITTER_ACCESS_TOKEN_SECRET
);

if (!$connection) {
    error_log("Failed to initialize TwitterOAuth.");
    die("Failed to initialize TwitterOAuth.");
}

// Test API authentication
$auth_response = $connection->get("account/verify_credentials");
echo "Authentication Test Response: " . json_encode($auth_response) . "\n";
error_log("Auth response: " . json_encode($auth_response));

if (isset($auth_response->errors)) {
    error_log("Authentication error: " . json_encode($auth_response->errors));
    die("Authentication error: " . json_encode($auth_response->errors));
}

// Check if we've hit the daily limit
$today = date('Y-m-d');
$statsQuery = "SELECT * FROM tweet_stats WHERE date = ? LIMIT 1";
$statsStmt = $conn->prepare($statsQuery);
$statsStmt->bind_param('s', $today);
$statsStmt->execute();
$result = $statsStmt->get_result();
$stats = $result->fetch_assoc();

if (!$stats) {
    $conn->query("INSERT INTO tweet_stats (date, tweets_sent) VALUES ('$today', 0)");
    $stats = ['tweets_sent' => 0];
}

if ($stats['tweets_sent'] >= 17) {
    echo "Daily tweet limit reached. Exiting.\n";
    exit;
}

// Get untweeted codes (3 at a time)
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

$timeOfDay = (int)date('H') < 12 ? 'Morning' : ((int)date('H') < 17 ? 'Afternoon' : 'Evening');
$tweetText = "🚙 New Rivian Referral Codes - {$timeOfDay} Update\n\n";
foreach ($codes as $index => $code) {
    $tweetText .= ($index + 1) . ". {$code['name']}: {$code['referral_code']}\n";
}
$tweetText .= "\n➡️ Visit: codetoadventure.com\n#Rivian #R1T #R1S";

// Post to Twitter using OAuth 1.0a
$post_response = $connection->post("statuses/update", ["status" => $tweetText]);
error_log("Post response: " . json_encode($post_response));

if (isset($post_response->id_str)) {
    $tweet_id = $post_response->id_str;
    $batch_id = uniqid();

    foreach ($codes as $code) {
        $updateSql = "UPDATE pending_tweets SET tweeted = 1, tweet_id = ?, batch_id = ?, last_attempt = NOW() WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('ssi', $tweet_id, $batch_id, $code['id']);
        $updateStmt->execute();
    }

    $conn->query("UPDATE tweet_stats SET tweets_sent = tweets_sent + 1, last_tweet_time = NOW() WHERE date = '$today'");
    echo "Successfully posted tweet with ID: " . $tweet_id . "\n";
} else {
    foreach ($codes as $code) {
        $conn->query("UPDATE pending_tweets SET attempts = attempts + 1, last_attempt = NOW() WHERE id = {$code['id']}");
    }
    echo "Failed to post tweet. Response: " . json_encode($post_response) . "\n";
}

$conn->close();
