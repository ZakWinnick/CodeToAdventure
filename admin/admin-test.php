<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Authentication checks (your existing session validation code)
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

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 25;
$offset = ($page - 1) * $entries_per_page;

// Get sorting parameters
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

// Validate sort column to prevent SQL injection
$allowed_columns = ['id', 'name', 'referral_code'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id';
}

// Validate sort direction
$sort_direction = strtoupper($sort_direction) === 'DESC' ? 'DESC' : 'ASC';

// Fetch total submissions count
$countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
$totalCount = $countResult->fetch_assoc()['total'];

// Calculate total pages
$total_pages = $entries_per_page === -1 ? 1 : ceil($totalCount / $entries_per_page);

// Fetch the latest submission
$latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
$latestSubmission = $latestResult->fetch_assoc();

// Prepare the main query
if ($entries_per_page === -1) {
    // Show all entries
    $query = "SELECT * FROM codes ORDER BY $sort_column $sort_direction";
} else {
    $query = "SELECT * FROM codes ORDER BY $sort_column $sort_direction LIMIT ? OFFSET ?";
}

// Execute the query
if ($entries_per_page === -1) {
    $allSubmissions = $conn->query($query);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $entries_per_page, $offset);
    $stmt->execute();
    $allSubmissions = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Submissions</title>
    <style>
        /* Your existing styles */
        
        /* Additional styles for pagination and sorting */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
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
            border-radius: 4px;
            background-color: #1a3e2b;
            color: #E7E7E5;
            border: 1px solid #87b485;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            background-color: #1a3e2b;
            color: #E7E7E5;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #2c5f2d;
        }

        .pagination .current {
            background-color: #87b485;
            color: #142a13;
        }

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

        @media (max-width: 768px) {
            .table-controls {
                flex-direction: column;
                align-items: flex-start;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
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
            </div>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="sortable <?php echo $sort_column === 'id' ? $sort_direction === 'ASC' ? 'asc' : 'desc' : ''; ?>" 
                        onclick="changeSort('id')">ID</th>
                    <th class="sortable <?php echo $sort_column === 'name' ? $sort_direction === 'ASC' ? 'asc' : 'desc' : ''; ?>" 
                        onclick="changeSort('name')">Name</th>
                    <th class="sortable <?php echo $sort_column === 'referral_code' ? $sort_direction === 'ASC' ? 'asc' : 'desc' : ''; ?>" 
                        onclick="changeSort('referral_code')">Referral Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
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

    <script>
        function changeEntries(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('entries', value);
            urlParams.set('page', 1); // Reset to first page when changing entries
            window.location.search = urlParams.toString();
        }

        function changeSort(column) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort');
            const currentDirection = urlParams.get('direction');
            
            if (currentSort === column) {
                // Toggle direction if clicking the same column
                urlParams.set('direction', currentDirection === 'ASC' ? 'DESC' : 'ASC');
            } else {
                // Default to ASC for new column
                urlParams.set('sort', column);
                urlParams.set('direction', 'ASC');
            }
            
            window.location.search = urlParams.toString();
        }
    </script>
</body>
</html>