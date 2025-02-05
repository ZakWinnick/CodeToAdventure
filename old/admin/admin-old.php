<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../config.php';

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 25;
$offset = ($page - 1) * $entries_per_page;

// Get sorting parameters
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'DESC';

// Validate sort parameters
$allowed_columns = ['id', 'name', 'referral_code', 'use_count', 'last_used'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id';
}
$sort_direction = strtoupper($sort_direction) === 'DESC' ? 'DESC' : 'ASC';

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_condition = '';
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $search_condition = " WHERE name LIKE '%$search%' OR referral_code LIKE '%$search%'";
}

// Total codes and claims
$totalQuery = "SELECT 
    COUNT(*) as total_codes,
    SUM(CASE WHEN last_used IS NOT NULL THEN 1 ELSE 0 END) as total_claims,
    SUM(use_count) as total_uses
FROM codes";
$totalResult = $conn->query($totalQuery);
$totals = $totalResult->fetch_assoc();

// Latest submission
$latestQuery = "SELECT * FROM codes ORDER BY id DESC LIMIT 1";
$latestResult = $conn->query($latestQuery);
$latest = $latestResult->fetch_assoc();

// Top codes
$topCodesQuery = "SELECT 
    name,
    referral_code,
    use_count,
    last_used
FROM codes
ORDER BY use_count DESC
LIMIT 10";
$topCodesResult = $conn->query($topCodesQuery);

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM codes" . $search_condition;
$countResult = $conn->query($countQuery);
$totalCount = $countResult->fetch_assoc()['total'];
$total_pages = ceil($totalCount / $entries_per_page);

// Get all submissions with sorting, search, and pagination
$query = "SELECT * FROM codes" . $search_condition . " ORDER BY $sort_column $sort_direction LIMIT $entries_per_page OFFSET $offset";
$allSubmissions = $conn->query($query);

// Calculate analytics data
$analyticsQuery = "SELECT 
    COUNT(*) as total_clicks,
    COUNT(DISTINCT ip_address) as unique_visitors,
    COUNT(CASE WHEN is_unique = 1 THEN 1 END) as unique_clicks
FROM code_analytics";
try {
    $analyticsResult = $conn->query($analyticsQuery);
    $analytics = $analyticsResult->fetch_assoc();
    $conversionRate = $analytics['total_clicks'] > 0 
        ? round(($analytics['unique_clicks'] / $analytics['total_clicks']) * 100, 1)
        : 0;
} catch (Exception $e) {
    $analytics = [
        'total_clicks' => 0,
        'unique_visitors' => 0,
        'unique_clicks' => 0
    ];
    $conversionRate = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/head.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code To Adventure - Admin Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../styles/base/_variables.css">
    <link rel="stylesheet" href="../styles/base/_reset.css">
    <link rel="stylesheet" href="../styles/components/_buttons.css">
    <link rel="stylesheet" href="../styles/components/_navigation.css">
    <link rel="stylesheet" href="../styles/components/_modal.css">
    <link rel="stylesheet" href="../styles/components/_toast.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/nav.php'; ?>

<!--<body>
    <header class="header">
        <a href="/" class="logo-container">Code To Adventure</a>
        <div class="nav-content">
            <a href="../index.php" class="nav-link">Home</a>
            <a href="../api-docs.php" class="nav-link">API Docs</a>
            <a href="../changelog.php" class="nav-link">Changelog</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </header>

    <nav class="nav-container">
        <div class="nav-content">
            <span class="nav-link">Admin Dashboard</span>
        </div>
    </nav> -->

    <main class="dashboard-content">
        <!-- Overview Cards -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3>Total Codes</h3>
                <p class="metric-value"><?php echo number_format($totals['total_codes']); ?></p>
            </div>
            <div class="metric-card">
                <h3>Total Uses</h3>
                <p class="metric-value"><?php echo number_format($totals['total_uses']); ?></p>
            </div>
            <div class="metric-card">
                <h3>Success Rate</h3>
                <p class="metric-value">
                    <?php 
                    $rate = $totals['total_codes'] > 0 
                        ? round(($totals['total_claims'] / $totals['total_codes']) * 100, 1) 
                        : 0;
                    echo $rate . '%';
                    ?>
                </p>
            </div>
            <div class="metric-card">
                <h3>Active Today</h3>
                <p class="metric-value">
                    <?php 
                    $today = date('Y-m-d');
                    $activeQuery = "SELECT COUNT(*) as active FROM codes WHERE DATE(last_used) = '$today'";
                    $activeResult = $conn->query($activeQuery);
                    $active = $activeResult->fetch_assoc();
                    echo number_format($active['active']);
                    ?>
                </p>
            </div>
        </div>

        <!-- Latest Submission -->
        <div class="latest-submission">
            <h3 class="info-title">Latest Submission</h3>
            <div class="submission-details">
                <?php if ($latest): ?>
                    <p>Code: <strong><?php echo htmlspecialchars($latest['referral_code']); ?></strong></p>
                    <p>Submitted by: <strong><?php echo htmlspecialchars($latest['name']); ?></strong></p>
                    <p>Uses: <strong><?php echo number_format($latest['use_count']); ?></strong></p>
                <?php else: ?>
                    <p>No submissions yet</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-section">
            <div>
                <h3 class="info-title">Analytics Overview</h3>
                <div class="analytics-grid">
                    <div class="metric-card">
                        <h4>Total Clicks</h4>
                        <p><?php echo number_format($analytics['total_clicks']); ?></p>
                    </div>
                    <div class="metric-card">
                        <h4>Unique Visitors</h4>
                        <p><?php echo number_format($analytics['unique_visitors']); ?></p>
                    </div>
                    <div class="metric-card">
                        <h4>Conversion Rate</h4>
                        <p><?php echo $conversionRate; ?>%</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="info-title">Top Referral Codes</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Uses</th>
                                <th>Last Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($code = $topCodesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($code['name']); ?></td>
                                <td><?php echo htmlspecialchars($code['referral_code']); ?></td>
                                <td><?php echo number_format($code['use_count']); ?></td>
                                <td><?php echo $code['last_used'] ? date('M j, Y', strtotime($code['last_used'])) : 'Never'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- All Submissions -->
        <div style="max-width: calc(var(--max-width) - 4rem); margin: 0 auto;">
            <div class="info-section">
                <div class="full-width">
                    <h3 class="info-title">All Submissions</h3>
                    <!-- Search Form -->
                    <div class="search-container">
                        <form action="" method="GET" class="search-form">
                            <input type="text" 
                                   name="search" 
                                   value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search by name or code..."
                                   class="search-input">
                            <button type="submit" class="search-button">Search</button>
                        </form>
                    </div>

                    <!-- Table Controls -->
                    <div class="table-controls">
                        <div class="entries-selector">
                            <label for="entries">Show entries:</label>
                            <select id="entries" onchange="changeEntries(this.value)">
                                <option value="25" <?php echo $entries_per_page === 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo $entries_per_page === 50 ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?php echo $entries_per_page === 100 ? 'selected' : ''; ?>>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="sortable <?php echo $sort_column === 'id' ? strtolower($sort_direction) : ''; ?>" 
                                        onclick="changeSort('id')">ID</th>
                                    <th class="sortable <?php echo $sort_column === 'name' ? strtolower($sort_direction) : ''; ?>" 
                                        onclick="changeSort('name')">Name</th>
                                    <th class="sortable <?php echo $sort_column === 'referral_code' ? strtolower($sort_direction) : ''; ?>" 
                                        onclick="changeSort('referral_code')">Code</th>
                                    <th class="sortable <?php echo $sort_column === 'use_count' ? strtolower($sort_direction) : ''; ?>" 
                                        onclick="changeSort('use_count')">Uses</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                                    <td><?php echo number_format($row['use_count'] ?? 0); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this code?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">First</a>
                            <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Previous</a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        
                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>" 
                               class="<?php echo $i === $page ? 'current' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Next</a>
                            <a href="?page=<?php echo $total_pages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>&entries=<?php echo $entries_per_page; ?>&sort=<?php echo $sort_column; ?>&direction=<?php echo $sort_direction; ?>">Last</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            </div>
    </main>

    <script>
    function changeEntries(value) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('entries', value);
        urlParams.set('page', 1);
        if (urlParams.has('sort')) {
            const sort = urlParams.get('sort');
            const direction = urlParams.get('direction');
            urlParams.set('sort', sort);
            urlParams.set('direction', direction);
        }
        window.location.search = urlParams.toString();
    }

    function changeSort(column) {
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort');
        const currentDirection = urlParams.get('direction');
        
        if (currentSort === column) {
            urlParams.set('direction', currentDirection === 'ASC' ? 'DESC' : 'ASC');
        } else {
            urlParams.set('sort', column);
            urlParams.set('direction', 'ASC');
        }
        
        if (urlParams.has('entries')) {
            urlParams.set('entries', urlParams.get('entries'));
        }
        
        urlParams.set('page', 1);
        window.location.search = urlParams.toString();
    }
    </script>
</body>
</html>