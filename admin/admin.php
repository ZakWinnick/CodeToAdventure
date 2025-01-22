<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

// Check login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../config.php';

// Fetch metrics
$lastMonth = date('Y-m-d', strtotime('-30 days'));

// Total codes and claims
$totalQuery = "SELECT 
    COUNT(*) as total_codes,
    SUM(CASE WHEN last_used IS NOT NULL THEN 1 ELSE 0 END) as total_claims,
    SUM(use_count) as total_uses
FROM codes";
$totalResult = $conn->query($totalQuery);
$totals = $totalResult->fetch_assoc();

// Daily claims over last 30 days
$dailyQuery = "SELECT 
    DATE(last_used) as date,
    COUNT(*) as claims
FROM codes 
WHERE last_used >= '$lastMonth' AND last_used IS NOT NULL
GROUP BY DATE(last_used)
ORDER BY date ASC
LIMIT 30";
$dailyResult = $conn->query($dailyQuery);

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
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="dashboard">
    <header class="header" role="banner">
        <a href="/" class="logo-container">Code To Adventure</a>
        <div class="admin-controls">
            <span class="admin-user">Admin Dashboard</span>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </header>

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

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-container">
                <h3>Daily Claims (30 Days)</h3>
                <canvas id="claimsChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Top Referral Codes</h3>
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

        <!-- All Submissions Table -->
        <div class="submissions-container">
            <h3>All Submissions</h3>
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
                                <button class="edit" onclick="window.location.href='edit.php?id=<?php echo $row['id']; ?>';">Edit</button>
                                <button class="delete" onclick="if(confirm('Are you sure?')) window.location.href='delete.php?id=<?php echo $row['id']; ?>';">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
    // Initialize chart
    const ctx = document.getElementById('claimsChart').getContext('2d');
    const chartData = <?php 
        $dailyData = [];
        while ($row = $dailyResult->fetch_assoc()) {
            $dailyData[] = [
                'date' => $row['date'],
                'claims' => (int)$row['claims']
            ];
        }
        echo json_encode($dailyData); 
    ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.date),
            datasets: [{
                label: 'Daily Claims',
                data: chartData.map(d => d.claims),
                borderColor: '#ECF39E',
                backgroundColor: 'rgba(236, 243, 158, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: { color: '#fff' }
                },
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: { color: '#fff', maxRotation: 45, minRotation: 45 }
                }
            }
        }
    });
    </script>
</body>
</html>