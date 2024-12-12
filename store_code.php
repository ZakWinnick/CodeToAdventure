<?php
include 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $referralCode = htmlspecialchars(trim($_POST['referralCode']));

    // Check if the referral code already exists in the database
    $stmt = $conn->prepare("SELECT id FROM codes WHERE referral_code = ?");
    $stmt->bind_param("s", $referralCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the referral code already exists, redirect with an error
    if ($result->num_rows > 0) {
        header('Location: submit.php?error=duplicate'); // Redirect to submit page with error
        exit();
    }

    // Prepare and bind the SQL query to insert the referral data
    $stmt = $conn->prepare("INSERT INTO codes (name, username, referral_code) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $username, $referralCode);

    // Execute the statement to insert the data
    if ($stmt->execute()) {
        // Send email notification
        $to = "admin@codetoadventure.com";  // Replace this with the email address where you want to receive submissions
        $subject = "New Referral Code Submitted";
        
        // Email body message
        $message = "A new referral code has been submitted:\n\n";
        $message .= "Name: $name\n";
        $message .= "Referral Code: $referralCode\n";
        
        // Email headers (optional)
        $headers = "From: no-reply@codetoadventure.com" . "\r\n" .
                   "Reply-To: no-reply@codetoadventure.com" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send the email using PHP's mail() function
        if (mail($to, $subject, $message, $headers)) {
            // Redirect to index page (or show success message)
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
