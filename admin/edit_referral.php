<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../config.php';

// Get the referral ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query to fetch the existing referral data
    $stmt = $conn->prepare("SELECT * FROM codes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if referral exists
    if ($result->num_rows > 0) {
        $referral = $result->fetch_assoc();
    } else {
        echo "Referral not found!";
        exit();
    }

    $stmt->close();
} else {
    echo "No referral ID provided!";
    exit();
}

// Handle the form submission to update the referral code
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $referralCode = htmlspecialchars(trim($_POST['referralCode']));

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE codes SET name = ?, username = ?, referral_code = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $username, $referralCode, $id);

    // Execute the query and check for success
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php"); // Redirect to the dashboard after success
        exit();
    } else {
        echo "Error updating referral: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Referral - Admin Panel</title>
    <style>
        /* Include your existing styles here */
    </style>
</head>
<body>

<header>Code to Adventure - Edit Referral</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="main-content">
    <div class="container">
        <h2>Edit Referral</h2>
        <form action="edit_referral.php?id=<?php echo $referral['id']; ?>" method="POST">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($referral['name']); ?>" required><br><br>

            <label for="username">X Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($referral['username']); ?>" required><br><br>

            <label for="referralCode">Referral Code</label>
            <input type="text" name="referralCode" value="<?php echo htmlspecialchars($referral['referral_code']); ?>" required><br><br>

            <button type="submit">Update Referral</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>
