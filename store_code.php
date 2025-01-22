<?php
include 'config.php';

header('Content-Type: application/json');

try {
    // Get and validate the submitted data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $referralCode = isset($_POST['referralCode']) ? trim($_POST['referralCode']) : '';

    // Validate inputs
    if (empty($name) || empty($referralCode)) {
        throw new Exception('Name and referral code are required');
    }

    // Validate referral code format (3 letters followed by 7 numbers)
    if (!preg_match('/^[A-Z]{3}\d{7}$/', $referralCode)) {
        throw new Exception('Invalid referral code format');
    }

    // Check if code already exists
    $checkSql = "SELECT COUNT(*) as count FROM codes WHERE referral_code = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $referralCode);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        throw new Exception('This referral code already exists');
    }

    // Insert the new code
    $sql = "INSERT INTO codes (name, referral_code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $name, $referralCode);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Code submitted successfully!'
        ]);
    } else {
        throw new Exception('Error saving the code');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();