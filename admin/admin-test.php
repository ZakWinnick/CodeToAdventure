<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Authentication check
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    if (isset($_COOKIE['login_token'])) {
        $token = $_COOKIE['login_token'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE login_token = ? AND token_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
        } else {
            setcookie('login_token', '', time() - 3600, '/');
        }
    }

    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }
}

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 25;
$offset = ($page - 1) * $entries_per_page;

// Sorting setup
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

// Security: Validate sort parameters
$allowed_columns = ['id', 'name', 'referral_code'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id';
}
$sort_direction = strtoupper($sort_direction) === 'DESC' ? 'DESC' : 'ASC';

// Get total count and latest submission
$countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
$totalCount = $countResult->fetch_assoc()['total'];
$total_pages = $entries_per_page === -1 ? 1 : ceil($totalCount / $entries_per_page);

$latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
$latestSubmission = $latestResult->fetch_assoc();

// Main data query
if ($entries_per_page === -1) {
    $query = "SELECT * FROM codes ORDER BY $sort_column $sort_direction";
    $allSubmissions = $conn->query($query);
} else {
    $query = "SELECT * FROM codes ORDER BY $sort_column $sort_direction LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $entries_per_page, $offset);
    $stmt->execute();
    $allSubmissions = $stmt->get_result();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Submissions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Base reset and defaults */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Core body styles */
        body {
            font-family: 'Lato', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #142a13;
            color: #E7E7E5;
            min-height: 100vh;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Main container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #123A13;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Navigation bar */
        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }

        .home {
            background-color: #87b485;
            color: #142a13;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .home:hover {
            background-color: #6f946f;
        }

        .logout {
            color: #f44336;
            text-decoration: none;
            font-weight: bold;
        }

        /* Header section */
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #E7E7E5;
            font-size: 1.8rem;
        }

        /* Summary cards */
        .summary {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .summary > div {
            flex: 1;
            min-width: 250px;
            background-color: #1a3e2b;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .summary h2 {
            color: #87b485;
            margin-bottom: 0.5rem;
            font-size: 1.4rem;
        }

        /* Table controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #1a3e2b;
            border-radius: 8px;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .entries-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .entries-selector select {
            padding: 0.5rem;
            background-color: #123A13;
            color: #E7E7E5;
            border: 1px solid #87b485;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            background-color: #87b485;
            color: #142a13;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #6f946f;
        }

        .pagination .current {
            background-color: #142a13;
            color: #87b485;
            border: 1px solid #87b485;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: #1a3e2b;
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #2c5f2d;
        }

        table th {
            background-color: #2c5f2d;
            font-weight: bold;
            color: #E7E7E5;
        }

        table tr:nth-child(even) {
            background-color: #1a3e2b;
        }

        table tr:hover {
            background-color: #3a6f4a;
        }

        /* Sortable columns */
        .sortable {
            cursor: pointer;
            position: relative;
            padding-right: 1.5rem;
        }

        .sortable::after {
            content: '↕';
            position: absolute;
            right: 0.5rem;
            opacity: 0.5;
        }

        .sortable.asc::after {
            content: '↑';
            opacity: 1;
        }

        .sortable.desc::after {
            content: '↓';
            opacity: 1;
        }

        /* Action buttons */
        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .actions button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .edit {
            background-color: #87b485;
            color: #142a13;
        }

        .edit:hover {
            background-color: #6f946f;
        }

        .delete {
            background-color: #f44336;
            color: white;
        }

        .delete:hover {
            background-color: #d32f2f;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #2c5f2d;
            color: white;
            padding: 10px 15px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .back-to-top:hover {
            background-color: #256c21;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                margin: 1rem auto;
            }

            .summary {
                flex-direction: column;
            }

            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .entries-selector {
                justify-content: center;
            }

            .pagination {
                justify-content: center;
            }

            .actions {
                flex-direction: column;
            }

            table th,
            table td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }

            .container {
                padding: 0.5rem;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            table th,
            table td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .actions button {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar">
            <a href="/index.php" class="home">Home</a>
            <a href="logout.php" class="logout">Logout</a>
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
                    <p>Code: <?php echo htmlspecialchars($latestSubmission['referral_code']); ?></p>
                    <p>Submitted by: <?php echo htmlspecialchars($latestSubmission['name']); ?></p>
                <?php else: ?>
                    <p>No submissions yet</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-controls">
            <div class="entries-selector">
                <label for="entries">Show entries:</label>
                <select id="entries" onchange="changeEntries(this.value)">
                    <option value="25" <?php echo $entries_per_page === 25 ? 'selected' : ''; ?>>25</option>
                    <option value="50" <?php echo $entries_per_page === 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo $entries_per_page === 100 ? 'selected' : ''; ?>>100</option>
                    <option value="-1" <?php echo $entries_per_page === -1 ? 'selected' : ''; ?>>All</option>
                </select>
            </div>

            <?php if ($entries_per_page !== -1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">First</a>
                    <a href="?page=<?php echo $page-1; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Previous</a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);
                
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>" 
                       class="<?php echo $i === $page ? 'current' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page+1; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Next</a>
                    <a href="?page=<?php echo $total_pages; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Last</a>
                <?php endif; ?>