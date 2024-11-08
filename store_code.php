<?php
include 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $referralCode = htmlspecialchars(trim($_POST['referralCode']));

    // Check for duplicates
    $stmt = $conn->prepare("SELECT * FROM codes WHERE referral_code = ?");
    $stmt->bind_param("s", $referralCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect back with an error if duplicate found
        header("Location: submit.php?error=duplicate");
        exit();
    } else {
        // Prepare and bind for insertion
        $stmt = $conn->prepare("INSERT INTO codes (name, username, referral_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $referralCode);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back with a success message
            header("Location: submit.php?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error; // Handle errors in execution
        }

        $stmt->close();
    }
}

$conn->close();
?>
