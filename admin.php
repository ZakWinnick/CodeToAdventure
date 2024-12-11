<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Submissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #eaf4ea;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }

        .summary .block {
            text-align: center;
        }

        .summary .block h2 {
            font-size: 1.5rem;
            color: #2c5f2d;
        }

        .summary .block p {
            font-size: 1rem;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c5f2d;
            color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .login-form {
            max-width: 400px;
            margin: 5rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-form h1 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #333;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-form button {
            width: 100%;
            padding: 0.75rem;
            background: #2c5f2d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        .login-form button:hover {
            background: #256c21;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    
    // Mock username and password for simplicity
    $username = 'admin';
    $password = 'password123';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['username'] === $username && $_POST['password'] === $password) {
            $_SESSION['loggedin'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $loginError = 'Invalid username or password';
        }
    }

    if (!isset($_SESSION['loggedin'])) {
    ?>
        <div class="login-form">
            <h1>Admin Login</h1>
            <?php if (!empty($loginError)): ?>
                <p style="color: red;"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    <?php
        exit;
    }
    ?>

    <div class="container">
        <div class="header">
            <h1>Admin Panel - Rivian Referral Submissions</h1>
        </div>

        <?php
        include 'config.php';

        // Fetch total count of submissions
        $countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
        $countData = $countResult->fetch_assoc();
        $totalCount = $countData['total'];

        // Fetch latest submission
        $latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
        $latestSubmission = $latestResult->fetch_assoc();

        // Fetch all submissions
        $allSubmissions = $conn->query("SELECT * FROM codes ORDER BY id ASC");
        ?>

        <div class="summary">
            <div class="block">
                <h2>Total Submissions</h2>
                <p><?php echo $totalCount; ?> codes submitted</p>
            </div>
            <div class="block">
                <h2>Latest Submission</h2>
                <?php if ($latestSubmission): ?>
                    <p>Code: <?php echo $latestSubmission['referral_code']; ?></p>
                    <p>Submitted by: <?php echo $latestSubmission['name']; ?></p>
                <?php else: ?>
                    <p>No submissions yet</p>
                <?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Referral Code</th>
                    <th>Submitted By</th>
                    <th>Submission Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['referral_code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['submission_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
