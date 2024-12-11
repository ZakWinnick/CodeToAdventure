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

        .logout {
            text-align: right;
            margin-bottom: 1rem;
        }

        .logout a {
            text-decoration: none;
            color: #f44336;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table th, table td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c5f2d;
            color: white;
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
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2c5f2d;
            color: white;
            padding: 10px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .back-to-top:hover {
            background-color: #256c21;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'config.php';

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }

    $countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
    if (!$countResult) {
        die("Count query failed: " . $conn->error);
    }
    $countData = $countResult->fetch_assoc();
    $totalCount = $countData['total'];

    $latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
    if (!$latestResult) {
        die("Latest query failed: " . $conn->error);
    }
    $latestSubmission = $latestResult->fetch_assoc();

    $allSubmissions = $conn->query("SELECT * FROM codes ORDER BY id ASC");
    if (!$allSubmissions) {
        die("All submissions query failed: " . $conn->error);
    }
    ?>
    <div class="container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <div class="header">
            <h1>Admin Panel - Submissions</h1>
        </div>

        <div class="summary">
            <div>
                <h2>Total Submissions</h2>
                <p><?php echo $totalCount; ?> codes submitted</p>
            </div>
            <div>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['referral_code']; ?></td>
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
    </div>

    <a href="#" class="back-to-top">Back to Top</a>
</body>
</html>