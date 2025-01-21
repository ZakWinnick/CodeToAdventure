<?php
include 'config.php';

// Set JSON content type header
header('Content-Type: application/json');

try {
    // Get random referral code, excluding the current one if provided
    $currentCode = isset($_GET['current']) ? $_GET['current'] : '';
    
    if ($currentCode) {
        $sql = "SELECT * FROM codes WHERE referral_code != ? ORDER BY RAND() LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $currentCode);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
    }
    
    if ($result && $referral = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'code' => [
                'name' => htmlspecialchars($referral['name']),
                'referral_code' => htmlspecialchars($referral['referral_code'])
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No codes found in database'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

// Close database connection
$conn->close();