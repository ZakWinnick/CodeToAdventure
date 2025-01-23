<?php
// Assuming this is part of store_code.php

// Database connection and code storage logic
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';

    if (!empty($name) && !empty($code)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO referral_codes (name, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $code);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Send email notification
            $to = 'zak@codetoadventure.com'; // Replace with your email address
            $subject = 'New Referral Code Submitted';
            $message = "A new referral code has been submitted:\n\nName: $name\nCode: $code";
            $headers = "From: noreply@codetoadventure.com"; // Update with your domain

            // Use PHP's mail function
            if (mail($to, $subject, $message, $headers)) {
                echo "Code stored successfully and email sent.\n\nEntered Details:\nName: $name\n\nCode: $code";
            } else {
                echo "Code stored successfully but failed to send email.\n\nEntered Details:\nName: $name\n\nCode: $code";
            }
        } else {
            echo "Failed to store the code.\n\nEntered Details:\nName: $name\n\nCode: $code";
        }

        $stmt->close();
    } else {
        echo 'Name and code are required.';
    }
}

$conn->close();
?>
