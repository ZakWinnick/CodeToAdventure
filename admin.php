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

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .actions button {
            padding: 0.5rem;
            font-size: 0.875rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .actions .edit {
            background-color: #4CAF50;
            color: white;
        }

        .actions .delete {
            background-color: #f44336;
            color: white;
        }

        .back-to-top {
            text-align: center;
            margin-top: 2rem;
        }

        .back-to-top a {
            text-decoration: none;
            color: #2c5f2d;
            font-weight: bold;
        }

        .back-to-top a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'config.php';

    // Login handling
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $loginError = 'Invalid username or password';
            }
        } else {
            $loginError = 'Invalid username or password';
        }

        $stmt->close();
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
                    <th>Name</th>
                    <th>Referral Code</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['referral_code']; ?></td>
                        <td><?php echo $row['submission_date']; ?></td>
                        <td>
                            <div class="actions">
                                <button class="edit" onclick="window.location.href='edit.php?id=<?php echo $row['id']; ?>';">Edit</button>
                                <button class="delete" onclick="if(confirm('Are you sure you want to delete this code?')) window.location.href='delete.php?id=<?php echo $row['id']; ?>';">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-to-top">
            <a href="#">Back to Top</a>
        </div>
    </div>
</body>
</html>
