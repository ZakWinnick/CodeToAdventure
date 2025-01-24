<?php
include 'config.php';

// Enable error logging
error_log("get_new_code.php started");

// Set JSON content type header
header('Content-Type: application/json');

try {
    // Get current code from query parameter
    $currentCode = isset($_GET['current']) ? trim($_GET['current']) : '';
    error_log("Current code: " . $currentCode);

    // Get random referral code
    if (!empty($currentCode)) {
        $sql = "SELECT * FROM codes WHERE referral_code != ? ORDER BY RAND() LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $currentCode);
        $stmt->execute();
        $result = $stmt->get_result();
        error_log("Executed query with exclusion: " . $sql);
    } else {
        $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
        error_log("Executed simple random query");
    }
    
    if ($result && $referral = $result->fetch_assoc()) {
        error_log("Found code: " . $referral['referral_code']);
        $response = [
            'success' => true,
            'code' => [
                'name' => htmlspecialchars($referral['name']),
                'referral_code' => htmlspecialchars($referral['referral_code'])
            ]
        ];
        error_log("Sending response: " . json_encode($response));
        echo json_encode($response);
    } else {
        error_log("No codes found in database");
        echo json_encode([
            'success' => false,
            'message' => 'No codes found in database'
        ]);
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

// Close database connection
$conn->close();