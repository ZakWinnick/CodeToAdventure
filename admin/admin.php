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

// Total codes and claims
$totalQuery = "SELECT 
    COUNT(*) as total_codes,
    SUM(CASE WHEN last_used IS NOT NULL THEN 1 ELSE 0 END) as total_claims,
    SUM(use_count) as total_uses
FROM codes";
$totalResult = $conn->query($totalQuery);
$totals = $totalResult->fetch_assoc();

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

// Get analytics data
$analyticsQuery = "SELECT 
    COUNT(*) as total_clicks,
    COUNT(DISTINCT ip_address) as unique_visitors,
    COUNT(DISTINCT referral_code) as codes_used,
    COUNT(CASE WHEN is_unique = 1 THEN 1 END) as unique_clicks
FROM code_analytics
WHERE timestamp > DATE_SUB(NOW(), INTERVAL 30 DAY)";
$analyticsResult = $conn->query($analyticsQuery);
$analytics = $analyticsResult->fetch_assoc();

// Get top countries
$countriesQuery = "SELECT 
    country,
    COUNT(*) as visits
FROM code_analytics
WHERE country != ''
GROUP BY country
ORDER BY visits DESC
LIMIT 5";
$countriesResult = $conn->query($countriesQuery);

// Get all codes for bottom table
$query = "SELECT * FROM codes ORDER BY id DESC";
$allSubmissions = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code To Adventure - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header class="header">
        <a href="/" class="logo-container">Code To Adventure</a>
        <div class="nav-content">
            <a href="../index.php" class="nav-link">Home</a>
            <a href="#" class="nav-link">Submit Code</a>
            <a href="../api-docs.html" class="nav-link">API Docs</a>
            <a href="../changelog.html" class="nav-link">Changelog</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </header>

    <nav class="nav-container">
        <div class="nav-content">
            <span class="nav-link">Admin Dashboard</span>
        </div>
    </nav>

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
                        <p><?php 
                            $rate = $analytics['total_clicks'] > 0 
                                ? round(($analytics['unique_clicks'] / $analytics['total_clicks']) * 100, 1)
                                : 0;
                            echo $rate . '%';
                        ?></p>
                    </div>
                </div>

                <h4 class="subsection-title">Top Countries</h4>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($country = $countriesResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($country['country']); ?></td>
                            <td><?php echo number_format($country['visits']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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
        <div class="info-section">
            <div style="grid-column: 1 / -1;">
                <h3 class="info-title">All Submissions</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Uses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $allSubmissions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['referral_code']); ?></td>
                            <td><?php echo number_format($row['use_count']); ?></td>
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
        </div>
    </main>
</body>
</html>