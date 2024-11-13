<?php
include '../config.php';  // Include the configuration file to connect to the database

// Query to get the total number of submissions
$totalQuery = "SELECT COUNT(*) AS total_submissions FROM codes";
$result = $conn->query($totalQuery);
$row = $result->fetch_assoc();
$totalSubmissions = $row['total_submissions'];

// Fetch other data or referrals for the chart, if necessary
// Example: Retrieve all submissions (or limit them for pagination purposes)
$referralQuery = "SELECT * FROM codes ORDER BY id DESC";  // You can modify this query as needed
$referralResult = $conn->query($referralQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Code to Adventure</title>
    <style>
        /* Include your site's CSS here */
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
            font-size: 40px;
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

        .total-submissions {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            background-color: #1A1A1A;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        footer {
            padding: 20px;
            background-color: #222;
            color: #E7E7E5;
            text-align: center;
        }
    </style>
</head>
<body>

<header>Code to Adventure - Admin Dashboard</header>

<nav>
    <a href="../index.php">Home</a>
    <a href="submit.php">Submit Code</a>
    <a href="api-docs.html">API Docs</a>
    <a href="changelog.html">Changelog</a>
</nav>

<div class="main-content">
    <!-- Display the total number of submissions -->
    <div class="total-submissions">
        <strong>Total Submissions: </strong><?php echo $totalSubmissions; ?>
    </div>

    <!-- Table or chart displaying referral data -->
    <h2>Referral Code Submissions</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Referral Code</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through all the fetched referral data and display it
            while ($referral = $referralResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $referral['id'] . "</td>";
                echo "<td>" . $referral['name'] . "</td>";
                echo "<td>" . $referral['username'] . "</td>";
                echo "<td>" . $referral['referral_code'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

</body>
</html>

<?php
$conn->close();
?>
