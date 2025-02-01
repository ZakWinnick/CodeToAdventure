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
    die("ERROR: CaBundle.php not found! Please download and place it in twitteroauth/vendor/composer/ca-bundle/");
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

// Authenticate using API v2
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

// Use Twitter API v2 for authentication
$auth_response = $connection->get("users/me");
echo "Authentication Test Response: " . json_encode($auth_response) . "\n";
error_log("Auth response: " . json_encode($auth_response));

echo "Last HTTP Code: " . $connection->getLastHttpCode() . "\n";
error_log("Last HTTP Code: " . $connection->getLastHttpCode());

if (isset($auth_response->errors)) {
    error_log("Authentication error: " . json_encode($auth_response->errors));
    die("Authentication error: " . json_encode($auth_response->errors));
}

// Check Twitter API Rate Limits before posting
$rate_limits = $connection->get("application/rate_limit_status", ["resources" => "statuses"]);
echo "Rate Limit Status: " . json_encode($rate_limits) . "\n";
error_log("Rate Limit Status: " . json_encode($rate_limits));

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

// Post to Twitter using API v2 endpoint
$post_response = $connection->post("tweets", ["text" => $tweetText]);
if ($connection->getLastHttpCode() == 429) {
    echo "Rate limit reached. Waiting 15 minutes...\n";
    error_log("Rate limit reached. Sleeping for 900 seconds.");
    sleep(900); // Wait for 15 minutes before retrying
    $post_response = $connection->post("tweets", ["text" => $tweetText]);
}

echo "Post Response: " . json_encode($post_response) . "\n";
error_log("Post Response: " . json_encode($post_response));

echo "Last HTTP Code: " . $connection->getLastHttpCode() . "\n";
error_log("Last HTTP Code: " . $connection->getLastHttpCode());

$conn->close();
