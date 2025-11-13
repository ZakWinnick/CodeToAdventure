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
$allowed_columns = ['id', 'name', 'referral_code', 'use_count', 'display_count', 'last_used'];
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

// Most displayed codes
$mostDisplayedQuery = "SELECT
    name,
    referral_code,
    display_count,
    use_count
FROM codes
ORDER BY display_count DESC
LIMIT 10";
$mostDisplayedResult = $conn->query($mostDisplayedQuery);

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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code To Adventure - Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --background: #ffffff;
            --surface: #f8fafc;
            --surface-hover: #f1f5f9;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --radius-sm: 8px;
            --error: #ef4444;
            --success: #10b981;
        }

        [data-theme="dark"] {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #34d399;
            --accent: #fbbf24;
            --background: #0f172a;
            --surface: #1e293b;
            --surface-hover: #334155;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --shadow: rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: var(--surface-hover);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .theme-toggle {
            background: var(--surface-hover);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: var(--text);
            margin-left: 0.5rem;
        }

        .theme-toggle:hover {
            background: var(--border);
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Dashboard Header */
        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .dashboard-header p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px var(--shadow);
        }

        .metric-card h3 {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }

        /* Section Card */
        .section-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.75rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 1.25rem;
        }

        /* Analytics Grid */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .analytics-grid .metric-card h4 {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .analytics-grid .metric-card p {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }

        /* Search Container */
        .search-container {
            margin-bottom: 1.5rem;
        }

        .search-form {
            display: flex;
            gap: 0.75rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--background);
            color: var(--text);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-button {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px -3px var(--shadow);
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .entries-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .entries-selector label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .entries-selector select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--background);
            color: var(--text);
            cursor: pointer;
            font-size: 0.9rem;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--background);
        }

        .data-table thead {
            background: var(--surface);
            border-bottom: 2px solid var(--border);
        }

        .data-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .data-table th.sortable {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .data-table th.sortable:hover {
            color: var(--primary);
        }

        .data-table th.sortable::after {
            content: ' ↕';
            opacity: 0.3;
        }

        .data-table th.sortable.asc::after {
            content: ' ↑';
            opacity: 1;
            color: var(--primary);
        }

        .data-table th.sortable.desc::after {
            content: ' ↓';
            opacity: 1;
            color: var(--primary);
        }

        .data-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: var(--surface-hover);
        }

        .data-table td {
            padding: 1rem;
            font-size: 0.9rem;
            color: var(--text);
        }

        /* Action Buttons */
        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .edit-btn,
        .delete-btn {
            padding: 0.4rem 0.875rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background: var(--primary);
            color: white;
        }

        .edit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .delete-btn {
            background: var(--error);
            color: white;
        }

        .delete-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 0.5rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: var(--surface-hover);
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination a.current {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Latest Submission Card */
        .submission-details {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .submission-details p {
            font-size: 0.95rem;
            color: var(--text);
        }

        .submission-details strong {
            color: var(--primary);
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .metric-value {
                font-size: 1.5rem;
            }

            .search-form {
                flex-direction: column;
            }

            .table-controls {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .data-table {
                font-size: 0.85rem;
            }

            .data-table th,
            .data-table td {
                padding: 0.75rem 0.5rem;
            }

            .nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="admin.php" class="logo">
                <div class="logo-text">Code to Adventure</div>
            </a>

            <nav class="nav">
                <a href="../index.php" class="nav-link">Home</a>
                <a href="../api-docs.php" class="nav-link">API Docs</a>
                <a href="../changelog.php" class="nav-link">Changelog</a>
                <a href="logout.php" class="nav-link">Logout</a>
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                    <span id="theme-icon">🌙</span>
                </button>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Manage and monitor referral codes</p>
        </div>

        <!-- Overview Metrics -->
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
        <div class="section-card">
            <h3 class="section-title">Latest Submission</h3>
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

        <!-- Analytics Overview -->
        <div class="section-card">
            <h3 class="section-title">Analytics Overview</h3>
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

        <!-- Top Referral Codes -->
        <div class="section-card">
            <h3 class="section-title">Top Referral Codes (By Clicks)</h3>
            <div class="table-container">
                <table class="data-table" id="top-codes-table">
                    <thead>
                        <tr>
                            <th class="sortable" data-column="0" data-type="string">Name</th>
                            <th class="sortable" data-column="1" data-type="string">Code</th>
                            <th class="sortable" data-column="2" data-type="number">Uses</th>
                            <th class="sortable" data-column="3" data-type="string">Last Used</th>
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

        <!-- Most Displayed Codes -->
        <div class="section-card">
            <h3 class="section-title">Most Displayed Codes</h3>
            <div class="table-container">
                <table class="data-table" id="most-displayed-table">
                    <thead>
                        <tr>
                            <th class="sortable" data-column="0" data-type="string">Name</th>
                            <th class="sortable" data-column="1" data-type="string">Code</th>
                            <th class="sortable" data-column="2" data-type="number">Displays</th>
                            <th class="sortable" data-column="3" data-type="number">Clicks</th>
                            <th class="sortable" data-column="4" data-type="number">Click Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($code = $mostDisplayedResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($code['name']); ?></td>
                            <td><?php echo htmlspecialchars($code['referral_code']); ?></td>
                            <td><?php echo number_format($code['display_count']); ?></td>
                            <td><?php echo number_format($code['use_count']); ?></td>
                            <td><?php
                                $clickRate = $code['display_count'] > 0
                                    ? round(($code['use_count'] / $code['display_count']) * 100, 1)
                                    : 0;
                                echo $clickRate . '%';
                            ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Submissions -->
        <div class="section-card">
            <h3 class="section-title">All Submissions</h3>

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
                <table class="data-table" id="all-submissions-table">
                    <thead>
                        <tr>
                            <th class="sortable <?php echo $sort_column === 'id' ? strtolower($sort_direction) : ''; ?>" data-column="0" data-type="number">ID</th>
                            <th class="sortable <?php echo $sort_column === 'name' ? strtolower($sort_direction) : ''; ?>" data-column="1" data-type="string">Name</th>
                            <th class="sortable <?php echo $sort_column === 'referral_code' ? strtolower($sort_direction) : ''; ?>" data-column="2" data-type="string">Code</th>
                            <th class="sortable <?php echo $sort_column === 'display_count' ? strtolower($sort_direction) : ''; ?>" data-column="3" data-type="number">Displays</th>
                            <th class="sortable <?php echo $sort_column === 'use_count' ? strtolower($sort_direction) : ''; ?>" data-column="4" data-type="number">Clicks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                            <td><?php echo number_format($row['display_count'] ?? 0); ?></td>
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
    </main>

    <script>
    // Theme Toggle
    function initTheme() {
        const saved = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = saved || (prefersDark ? 'dark' : 'light');

        document.documentElement.setAttribute('data-theme', theme);
        updateThemeIcon(theme);
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateThemeIcon(next);
    }

    function updateThemeIcon(theme) {
        document.getElementById('theme-icon').textContent = theme === 'dark' ? '☀️' : '🌙';
    }

    // Initialize theme on load
    initTheme();

    // Table Functions
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

    // Table sorting - works for all tables
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers to all sortable headers
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const table = this.closest('table');
                const tbody = table.querySelector('tbody');
                const columnIndex = parseInt(this.getAttribute('data-column'));
                const dataType = this.getAttribute('data-type');

                // Determine sort direction
                const isAsc = this.classList.contains('asc');
                const isDesc = this.classList.contains('desc');
                const newDirection = isAsc ? 'desc' : 'asc';

                // Remove all sorting classes from this table's headers
                table.querySelectorAll('.sortable').forEach(th => {
                    th.classList.remove('asc', 'desc');
                });

                // Add new sorting class
                this.classList.add(newDirection);

                // Get all rows
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Sort rows
                rows.sort((a, b) => {
                    let aValue = a.cells[columnIndex].textContent.trim();
                    let bValue = b.cells[columnIndex].textContent.trim();

                    // Remove commas and % signs for numbers
                    if (dataType === 'number') {
                        aValue = parseFloat(aValue.replace(/[,%]/g, '')) || 0;
                        bValue = parseFloat(bValue.replace(/[,%]/g, '')) || 0;

                        return newDirection === 'asc' ? aValue - bValue : bValue - aValue;
                    } else {
                        // String comparison
                        const comparison = aValue.localeCompare(bValue);
                        return newDirection === 'asc' ? comparison : -comparison;
                    }
                });

                // Re-append sorted rows
                rows.forEach(row => tbody.appendChild(row));

                // Update URL for main submissions table only
                if (table.id === 'all-submissions-table') {
                    const urlParams = new URLSearchParams(window.location.search);
                    const columnName = this.textContent.trim().toLowerCase();
                    urlParams.set('direction', newDirection.toUpperCase());
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.pushState({}, '', newUrl);
                }
            });
        });
    });
    </script>
</body>
</html>
