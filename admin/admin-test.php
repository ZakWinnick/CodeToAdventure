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

// Get total count and calculate total pages
$countResult = $conn->query("SELECT COUNT(*) AS total FROM codes");
$totalCount = $countResult->fetch_assoc()['total'];
$total_pages = $entries_per_page === -1 ? 1 : ceil($totalCount / $entries_per_page);

// Get latest submission
$latestResult = $conn->query("SELECT * FROM codes ORDER BY id DESC LIMIT 1");
$latestSubmission = $latestResult->fetch_assoc();

// Get submissions with pagination
if ($entries_per_page === -1) {
    $query = "SELECT * FROM codes ORDER BY id DESC";
    $allSubmissions = $conn->query($query);
} else {
    $query = "SELECT * FROM codes ORDER BY id DESC LIMIT ? OFFSET ?";
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
    <title>Admin Test</title>
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
    </style>
</head>
<body>
    <div class="container">
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
                    <a href="?page=1&entries=<?php echo $entries_per_page; ?>">First</a>
                    <a href="?page=<?php echo $page-1; ?>&entries=<?php echo $entries_per_page; ?>">Previous</a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);
                
                for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&entries=<?php echo $entries_per_page; ?>" 
                       class="<?php echo $i === $page ? 'current' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page+1; ?>&entries=<?php echo $entries_per_page; ?>">Next</a>
                    <a href="?page=<?php echo $total_pages; ?>&entries=<?php echo $entries_per_page; ?>">Last</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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
</body>
</html>