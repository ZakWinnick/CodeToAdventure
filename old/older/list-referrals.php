<?php
include 'config.php';

// Initialize the search term variable
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Prepare SQL query with search functionality
$sql = "SELECT * FROM codes WHERE name LIKE ? OR username LIKE ? OR referral_code LIKE ? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$likeTerm = "%" . $searchTerm . "%";
$stmt->bind_param('sss', $likeTerm, $likeTerm, $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of referrals
$totalReferralsQuery = "SELECT COUNT(*) AS total FROM codes";
$totalReferralsResult = $conn->query($totalReferralsQuery);
$totalReferrals = $totalReferralsResult->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Referrals - Code to Adventure</title>
    <script src="https://tinylytics.app/embed/YOUR_EMBED_CODE.js" defer></script>
    <style>
        * {
            box-sizing: border-box; /* Ensure padding/margins don't exceed element size */
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
            overflow-x: hidden; /* Prevent horizontal scroll */
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
            color: #A7D3E0; /* Lighter link color */
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
            box-sizing: border-box;
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

        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between; /* Adjust for layout */
            align-items: center; /* Center items vertically */
        }

        .search-container input {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #046896;
            color: #E7E7E5;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-container button:hover {
            background-color: #B4232A;
        }

        .back-link {
            display: inline-block;
            color: #A7D3E0; /* Lighter link color */
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
            color: #A7D3E0; /* Lighter link color */
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Mobile-friendly adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 30px 10px; /* Adjust padding for mobile */
            }

            .search-container {
                margin-bottom: 15px; /* Adjust margin for mobile */
            }

            table {
                font-size: 14px; /* Adjust font size for mobile */
            }

            nav a {
                padding: 12px 10px;
                font-size: 16px;
            }

            .back-link {
                margin-top: 15px; /* Adjust margin for mobile */
            }
        }
    </style>
</head>
<body>

<header>Code to Adventure</header>

<nav>
    <a href="index.php">Home</a>
    <a href="submit.php">Submit Code</a>
    <a href="api-docs.html">API Docs</a>
    <a href="changelog.html">Changelog</a>
</nav>

<div class="main-content">
    <h2>All Referral Codes</h2>

    <div class="search-container">
        <form action="list-referrals.php" method="POST">
            <input type="text" name="search" placeholder="Search by Name, Username, or Code" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
        <span>Total Referrals: <strong><?php echo $totalReferrals; ?></strong></span> <!-- Show total referrals -->
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>X Username</th>
                <th>Referral Code</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                        <td><a href="https://rivian.com/configurations/list?reprCode=<?php echo htmlspecialchars($row['referral_code']); ?>" target="_blank">Use this Referral Code</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No referral codes available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="back-link">Back to Home</a>
</div>

<footer>
    Created by <a href="https://winnick.is" target="_blank">Zak Winnick</a> | <a href="https://zak.codetoadventure.com" target="_blank">Zak's Referral Code</a> | <a href="mailto:admin@codetoadventure.com">E-mail the admin</a> for any questions or assistance
</footer>

<?php $conn->close(); ?>
</body>
</html>
