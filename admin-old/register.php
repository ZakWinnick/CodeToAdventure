<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If username exists, show error
        $error_message = "Username is already taken. Please choose another one.";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        if ($stmt->execute()) {
            // Redirect to login page if successful
            header('Location: login.php');
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Admin Panel</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #E7E7E5;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }

        header {
            background-color: #046896;
            padding: 20px;
            text-align: center;
            color: #E7E7E5;
            font-size: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .container {
            max-width: 400px;
            background-color: #1A1A1A;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin: 20px auto;
        }

        input {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            width: 100%;
            background-color: #333;
            color: white;
            margin-bottom: 10px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            width: 100%;
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

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        footer a {
            color: #00acee;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>Admin Registration</header>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<footer>
    <a href="index.php">Back to Home</a>
</footer>

</body>
</html>
