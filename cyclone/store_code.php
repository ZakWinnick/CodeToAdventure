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

    // Validate referral code format (at least 3 letters and at least 7 numbers, no length limit)
    if (!preg_match('/^(?=(?:.*[A-Za-z]){3,})(?=(?:.*\d){7,})[A-Za-z0-9]+$/', $referralCode)) {
        throw new Exception('Invalid referral code format. The code must have at least 3 letters and 7 numbers.');
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
        // Send email notification
        $to = 'zak@codetoadventure.com'; // Replace with your email address
        $subject = 'New Referral Code Submitted';
        $message = "A new referral code has been submitted:\n\nName: $name\n\nReferral Code: $referralCode";
        $headers = "From: noreply@codetoadventure.com"; // Update with your domain

        if (!mail($to, $subject, $message, $headers)) {
            throw new Exception('Code saved, but email notification failed.');
        }

        echo json_encode([
            'success' => true,
            'message' => 'Code submitted successfully! Email notification sent.'
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
