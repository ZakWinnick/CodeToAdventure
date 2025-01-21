<?php
include 'config.php';

header('Content-Type: application/json');

try {
    // Get random referral code
    $sql = "SELECT * FROM codes ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $referral = $result->fetch_assoc();
    
    if ($referral) {
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
            'message' => 'No codes found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}

$conn->close();
?>