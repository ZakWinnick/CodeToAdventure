<?php
include 'config.php';

// Load Twitter credentials
$creds = require __DIR__ . '/credentials.php';

define('TWITTER_API_KEY', $creds['TWITTER_API_KEY']);
define('TWITTER_API_SECRET', $creds['TWITTER_API_SECRET']);
define('TWITTER_ACCESS_TOKEN', $creds['TWITTER_ACCESS_TOKEN']);
define('TWITTER_ACCESS_TOKEN_SECRET', $creds['TWITTER_ACCESS_TOKEN_SECRET']);

echo "API Key: " . substr(TWITTER_API_KEY, 0, 5) . "...\n";
echo "Access Token: " . substr(TWITTER_ACCESS_TOKEN, 0, 5) . "...\n";

// Test API authentication
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.twitter.com/1.1/account/verify_credentials.json",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . TWITTER_ACCESS_TOKEN,
        "Content-Type: application/json"
    ]
]);
$auth_response = curl_exec($ch);
curl_close($ch);
echo "Authentication Test Response: " . $auth_response . "\n";

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

// Post to Twitter
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.twitter.com/2/tweets',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . TWITTER_ACCESS_TOKEN,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode(["text" => $tweetText]),
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $responseData = json_decode($response, true);
    $tweet_id = $responseData['data']['id'];
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
    echo "Failed to post tweet. HTTP Code: $httpCode\n";
    echo "Response: " . $response . "\n";
}

$conn->close();
