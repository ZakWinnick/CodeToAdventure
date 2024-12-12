<?php
session_start();
include 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing submission details
    $stmt = $conn->prepare("SELECT * FROM codes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $submission = $result->fetch_assoc();

    if (!$submission) {
        die("No submission found with the given ID.");
    }
} else {
    die("Invalid ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $referral_code = $_POST['referral_code'];

    // Update the database
    $stmt = $conn->prepare("UPDATE codes SET name = ?, referral_code = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $referral_code, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        $error = "Failed to update submission. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Submission</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #142a13;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .edit-container {
            max-width: 400px;
            width: 100%;
            background-color: #123A13;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .edit-container h1 {
            margin-bottom: 1.5rem;
            color: #DEB526;
            font-size: 1.75rem;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #87b485;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #87b485;
            border-radius: 5px;
            background-color: #1a3e2b;
            color: #E7E7E5;
        }

        .form-group input:focus {
            border-color: #6f946f;
            outline: none;
        }

        .edit-button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: #142a13;
            background-color: #87b485;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-button:hover {
            background-color: #6f946f;
        }

        .back-button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: white;
            background-color: #f44336;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }

        .back-button:hover {
            background-color: #d32f2f;
        }

        .error-message {
            color: #f44336;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit Submission</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($submission['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="referral_code">Referral Code</label>
                <input type="text" id="referral_code" name="referral_code" value="<?php echo htmlspecialchars($submission['referral_code']); ?>" required>
            </div>
            <button type="submit" class="edit-button">Save Changes</button>
        </form>
        <button onclick="window.location.href='admin.php';" class="back-button">Back</button>
    </div>
</body>
</html>
