<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

require 'config.php';

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 25;
$offset = ($page - 1) * $entries_per_page;

// Get sorting parameters
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'DESC';

// Validate sort parameters
$allowed_columns = ['id', 'name', 'referral_code'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id';
}
$sort_direction = strtoupper($sort_direction) === 'DESC' ? 'DESC' : 'ASC';

// Get total count and calculate total pages
$countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
$totalCount = $countResult->fetch_assoc()['total'];
$total_pages = $entries_per_page === -1 ? 1 : ceil($totalCount / $entries_per_page);

// Get latest submission
$latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
$latestSubmission = $latestResult->fetch_assoc();

// Get submissions with pagination and sorting
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin panel for managing referral code submissions on Code to Adventure.">
    <meta name="author" content="Zak Winnick">

    <title>CTA Admin Panel</title>
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
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: #123A13;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #E7E7E5;
        }

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem 0;
        }

        .nav-bar a {
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .home {
            background-color: #87b485;
            color: #142a13;
        }

        .home:hover {
            background-color: #6f946f;
        }

        .logout {
            color: #f44336;
            text-decoration: none;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.5rem;
            color: var(--mindaro);
            margin-bottom: 1rem;
            text-align: center;
        }
      
        .summary {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .summary div {
            flex: 1;
            padding: 1rem;
            background-color: #1a3e2b;
            border-radius: 8px;
            text-align: center;
        }

        .summary h2 {
            color: #87b485;
            margin-bottom: 0.5rem;
        }

        /* Table controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #1a3e2b;
            border-radius: 8px;
            gap: 1rem;
            flex-wrap: wrap;
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
        }

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
        }

        .pagination a:hover {
            background-color: #6f946f;
        }

        .pagination .current {
            background-color: #142a13;
            color: #87b485;
            border: 1px solid #87b485;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table th, table td {
            padding: 1rem;
            border-bottom: 1px solid #2c5f2d;
            color: #E7E7E5;
        }

        table th {
            background-color: #2c5f2d;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #1a3e2b;
        }

        table tr:hover {
            background-color: #3a6f4a;
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
            background-color: #87b485;
            color: #142a13;
        }

        .actions .delete {
            background-color: #f44336;
            color: white;
        }

        .error-message {
            background-color: #f44336;
            color: #fff;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
}


        /* Footer styles */
        footer {
            background-color: var(--hunter-green);
            padding: 2rem 1rem;
            text-align: center;
            margin-top: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .footer-link {
            color: var(--mindaro);
            text-decoration: none;
            transition: opacity var(--transition-speed) ease;
        }

        .footer-link:hover {
             color: var(--moss-green);
        }

        /* Sortable column styles */
        .sortable {
            cursor: pointer;
            position: relative;
            padding-right: 2rem !important;
        }

        .sortable::after {
            content: '';
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #E7E7E5;
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
        }

        .sortable.asc::after {
            border-top: 0;
            border-bottom: 5px solid #87b485;
            opacity: 1;
        }

        .sortable.desc::after {
            border-top: 5px solid #87b485;
            opacity: 1;
        }

        .sortable:hover {
            background-color: #3a6f4a;
        }

        .sortable:hover::after {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
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

            table th, table td {
                font-size: 0.875rem;
                padding: 0.5rem;
            }

            .summary {
                flex-direction: column;
            }

            .actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
    body {
        padding: 1rem;
    }

    .container {
        padding: 0.75rem;
    }

    table th, table td {
        font-size: 0.75rem;
        padding: 0.5rem;
    }

    .actions button {
        font-size: 0.75rem;
        padding: 0.25rem;
    }

    .footer-links {
        flex-direction: column;
        gap: 0.75rem;
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

        <header class="header">
            <h1>Code to Adventure - Admin Panel</h1>
        </header>

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
                    <th class="sortable <?php echo $sort_column === 'id' ? strtolower($sort_direction) : ''; ?>" 
                        onclick="changeSort('id')">ID</th>
                    <th class="sortable <?php echo $sort_column === 'name' ? strtolower($sort_direction) : ''; ?>" 
                        onclick="changeSort('name')">Name</th>
                    <th class="sortable <?php echo $sort_column === 'referral_code' ? strtolower($sort_direction) : ''; ?>" 
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

        <script>
        function changeEntries(value) {
            const urlParams = new URLSearchParams(window.location.search);
            // Set new entries value and reset to first page
            urlParams.set('entries', value);
            urlParams.set('page', 1);
            // Maintain sort parameters
            if (urlParams.has('sort')) {
                const sort = urlParams.get('sort');
                const direction = urlParams.get('direction');
                urlParams.set('sort', sort);
                urlParams.set('direction', direction);
            }
            // Update URL with new parameters
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
            
            // Maintain entries per page
            if (urlParams.has('entries')) {
                urlParams.set('entries', urlParams.get('entries'));
            }
            
            // Reset to first page
            urlParams.set('page', 1);
            
            // Update URL with new parameters
            window.location.search = urlParams.toString();
        }
    </script>
</body>
<footer>
    <p>&copy; <script>document.write(new Date().getFullYear());</script>
        <a href="https://zakwinnick.com" class="footer-link" target="_blank" rel="noopener noreferrer">Zak Winnick</a>
    </p>
    <div class="footer-links">
        <a href="/index.php" class="footer-link" target="_blank" rel="noopener noreferrer">Home</a>
        <a href="mailto:admin@codetoadventure.com" class="footer-link" target="_blank" rel="noopener noreferrer">Contact Admin</a>
    </div>
</footer>
</html>