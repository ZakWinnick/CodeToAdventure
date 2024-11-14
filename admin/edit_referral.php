<?php
session_start(); // Start the session to check if the user is logged in

// If no user is logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page
    exit();  // Ensure no further code is executed
}

include '../config.php';  // Adjust the path as necessary

// Check if the ID is provided via URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the referral details from the database
    $sql = "SELECT * FROM codes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $referral = $result->fetch_assoc();
    
    if (!$referral) {
        // If no matching record is found, redirect or show an error
        header("Location: admin_dashboard.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $name = htmlspecialchars(trim($_POST['name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $referralCode = htmlspecialchars(trim($_POST['referralCode']));

    // Update the referral details in the database
    $updateSql = "UPDATE codes SET name = ?, username = ?, referral_code = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssi", $name, $username, $referralCode, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");  // Redirect back to the dashboard after successful update
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
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
    <title>Edit Referral - Code to Adventure</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #046896;
            padding: 20px;
            text-align: center;
            color: #E7E7E5;
            font-size: 36px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        nav {
            background-color: #B4232A;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #046896;
        }

        .main-content {
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #1A1A1A;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        label {
            font-size: 16px;
            color: #E7E7E5;
        }

        input, button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        input {
            background-color: #333;
            color: white;
        }

        button {
            background-color: #00acee;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #007bb5;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-block;
            color: #00acee;
            margin-top: 20px;
            text-decoration: none;
            border: 2px solid #00acee;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .back-link:hover {
            background-color: #00acee;
            color: #000;
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            text-align: center;
        }

        footer a {
            color: #00acee;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile-friendly adjustments */
        @media (max-width: 768px) {
            header {
                font-size: 28px;
            }

            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }

            .main-content {
                padding: 20px 10px;
            }

            .container {
                padding: 20px;
                width: 100%;
            }

            form {
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<header>Edit Referral Code</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="main-content">
    <div class="container">
        <h2>Edit Referral Code Information</h2>
        
        <!-- If there's an error, show the error message -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error">There was an error updating the referral code.</div>
        <?php endif; ?>

        <!-- The form to edit referral code -->
        <form action="edit_referral.php?id=<?php echo $referral['id']; ?>" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($referral['name']); ?>" required>

            <label for="username">X Username (without @)</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($referral['username']); ?>" required>

            <label for="referralCode">Referral Code (Just the code - No URL)</label>
            <input type="text" id="referralCode" name="referralCode" value="<?php echo htmlspecialchars($referral['referral_code']); ?>" required>

            <button type="submit">Update Referral Code</button>
        </form>

        <a href="admin_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>
