<?php
include 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $referralCode = htmlspecialchars(trim($_POST['referralCode']));

    // Prepare and bind the SQL query to insert the referral data
    $stmt = $conn->prepare("INSERT INTO codes (name, username, referral_code) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $username, $referralCode);

    // Execute the statement to insert the data
    if ($stmt->execute()) {
        // Send email notification
        $to = "your-email@example.com";  // Change this to the email address where you want to receive submissions
        $subject = "New Referral Code Submitted";
        
        // Email body message
        $message = "A new referral code has been submitted:\n\n";
        $message .= "Name: $name\n";
        $message .= "Username: $username\n";
        $message .= "Referral Code: $referralCode\n";
        
        // Email headers (optional)
        $headers = "From: no-reply@yourdomain.com" . "\r\n" .
                   "Reply-To: no-reply@yourdomain.com" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send the email using PHP's mail() function
        if (mail($to, $subject, $message, $headers)) {
            // Redirect to index page or show success message
            header('Location: index.php?status=success');
        } else {
            // If email fails to send
            echo "There was an error sending the email.";
        }
        exit();  // Make sure to exit after redirecting or outputting message
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
