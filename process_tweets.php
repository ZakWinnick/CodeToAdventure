<?php
include 'config.php';

// Load Twitter credentials
$creds = require __DIR__ . '/credentials.php';

define('TWITTER_API_KEY', $creds['TWITTER_API_KEY']);
define('TWITTER_API_SECRET', $creds['TWITTER_API_SECRET']);
define('TWITTER_ACCESS_TOKEN', $creds['TWITTER_ACCESS_TOKEN']);
define('TWITTER_ACCESS_TOKEN_SECRET', $creds['TWITTER_ACCESS_TOKEN_SECRET']);

// Check if we've hit the daily limit
$today = date('Y-m-d');
$statsQuery = "SELECT * FROM tweet_stats WHERE date = ? LIMIT 1";
$statsStmt = $conn->prepare($statsQuery);
$statsStmt->bind_param('s', $today);
$statsStmt->execute();
$result = $statsStmt->get_result();
$stats = $result->fetch_assoc();

if (!$stats) {
    // Create new stats for today
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

// Group codes for the tweet
$codes = [];
while ($row = $result->fetch_assoc()) {
    $codes[] = $row;
}

// Create tweet text
$timeOfDay = '';
$hour = (int)date('H');
if ($hour >= 5 && $hour < 12) {
    $timeOfDay = 'Morning';
} elseif ($hour >= 12 && $hour < 17) {
    $timeOfDay = 'Afternoon';
} else {
    $timeOfDay = 'Evening';
}

$tweetText = "🚙 New Rivian Referral Codes - {$timeOfDay} Update\n\n";
foreach ($codes as $index => $code) {
    $tweetText .= ($index + 1) . ". {$code['name']}: {$code['referral_code']}\n";
}
$tweetText .= "\n➡️ Visit: codetoadventure.com\n#Rivian #R1T #R1S";

// Post to Twitter
function generate_oauth_signature($method, $url, $params, $oauth_token_secret) {
    $parts = [];
    ksort($params);
    foreach($params as $key => $value) {
        $parts[] = rawurlencode($key) . '=' . rawurlencode($value);
    }
    $base_string = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $parts));
    $signing_key = rawurlencode(TWITTER_API_SECRET) . '&' . rawurlencode($oauth_token_secret);
    return base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));
}

$url = 'https://api.twitter.com/1.1/statuses/update.json';
$oauth_nonce = time();
$oauth_timestamp = time();

$oauth_params = [
    'oauth_consumer_key' => TWITTER_API_KEY,
    'oauth_nonce' => $oauth_nonce,
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => $oauth_timestamp,
    'oauth_token' => TWITTER_ACCESS_TOKEN,
    'oauth_version' => '1.0',
    'status' => $tweetText
];

$signature = generate_oauth_signature('POST', $url, $oauth_params, TWITTER_ACCESS_TOKEN_SECRET);

$header_params = $oauth_params;
unset($header_params['status']);
$header_params['oauth_signature'] = $signature;

$auth_header = 'OAuth ';
$header_parts = [];
foreach ($header_params as $key => $value) {
    $header_parts[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
}
$auth_header .= implode(', ', $header_parts);

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
$responseData = json_decode($response, true);

if ($httpCode === 200 && isset($responseData['id_str'])) {
    // Update tweet status and stats
    $tweet_id = $responseData['id_str'];
    $batch_id = uniqid();
    
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
    
    // Update daily stats
    $conn->query("UPDATE tweet_stats SET 
        tweets_sent = tweets_sent + 1,
        last_tweet_time = NOW()
        WHERE date = '$today'");
        
    echo "Successfully posted tweet with ID: " . $tweet_id . "\n";
} else {
    // Update attempt count
    foreach ($codes as $code) {
        $conn->query("UPDATE pending_tweets SET 
            attempts = attempts + 1,
            last_attempt = NOW()
            WHERE id = {$code['id']}");
    }
    echo "Failed to post tweet. HTTP Code: $httpCode\n";
    echo "Response: " . $response . "\n";
}

curl_close($ch);
$conn->close();