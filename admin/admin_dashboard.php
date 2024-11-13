<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../config.php';

// Fetch all referrals from the database
$sql = "SELECT * FROM codes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Code to Adventure</title>
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            overflow-x: hidden;
        }

        header {
            background-color: #046896;
            padding: 20px;
            text-align: center;
            color: #E7E7E5;
            font-size: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        nav {
            background-color: #B4232A;
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav a {
            color: #E7E7E5;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #046896;
        }

        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            padding: 40px 20px;
            margin: 0 auto;
            text-align: center;
            box-sizing: border-box;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #1A1A1A;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1A1A1A;
            color: #00acee;
        }

        tr:nth-child(even) {
            background-color: #222;
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

<header>Admin Dashboard</header>

<nav>
    <a href="index.php">Home</a>
    <a href="submit.php">Submit Code</a>
    <a href="api-docs.html">API Docs</a>
    <a href="changelog.html">Changelog</a>
</nav>

<div class="main-content">
    <div class="container">
        <h2>Referral Codes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Referral Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                        <td>
                            <a href="edit_referral.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_referral.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="logout.php" class="back-link">Logout</a>
    </div>
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="https://zak.codetoadventure.com" target="_blank">Zak's Referral Code</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>
