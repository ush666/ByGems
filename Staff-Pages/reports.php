<?php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in or if customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../login/customer_login.php");
    exit();
}

// 1. REVENUE METRICS
$current_week_revenue = $pdo->query("
    SELECT IFNULL(SUM(total_amount), 0) 
    FROM orders 
    WHERE WEEK(order_date) = WEEK(CURDATE()) 
    AND payment_status = 'fullypaid'
")->fetchColumn();

$last_week_revenue = $pdo->query("
    SELECT IFNULL(SUM(total_amount), 0) 
    FROM orders 
    WHERE WEEK(order_date) = WEEK(CURDATE()) - 1 
    AND payment_status = 'fullypaid'
")->fetchColumn();

$revenue_increase = ($last_week_revenue > 0)
    ? round((($current_week_revenue - $last_week_revenue) / $last_week_revenue) * 100)
    : (($current_week_revenue > 0) ? 100 : 0);

// 2. COMPLETION RATES

// Count of approved (completed) events
$completed_orders = $pdo->query("
    SELECT COUNT(*) 
    FROM event_request 
    WHERE request_status = 'approved'
")->fetchColumn();

// Count of total orders from last month
$total_orders_last_month = $pdo->query("
    SELECT COUNT(*) 
    FROM orders 
    WHERE MONTH(order_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
")->fetchColumn();

// Calculate completion and incomplete rates
$completion_rate = $total_orders_last_month > 0 ? round(($completed_orders / $total_orders_last_month) * 100) : 0;
$incomplete_rate = 100 - $completion_rate;


// 3. QUARTERLY PROGRESS
$quarter_start = date('Y-m-d', strtotime('first day of this quarter'));
$quarter_end = date('Y-m-d', strtotime('last day of this quarter'));
$quarter_days_passed = (strtotime(date('Y-m-d')) - strtotime($quarter_start)) / (60 * 60 * 24);
$quarter_days_total = (strtotime($quarter_end) - strtotime($quarter_start)) / (60 * 60 * 24);
$quarter_progress = $quarter_days_total > 0 ? round(($quarter_days_passed / $quarter_days_total) * 100) : 0;

// 4. REVENUE GROWTH DATA
$growth_data = [];
$current_year = date('Y');
for ($i = 0; $i < 8; $i++) {
    $year = $current_year - 7 + $i;
    $revenue = $pdo->query("
        SELECT IFNULL(SUM(total_amount), 0) 
        FROM orders 
        WHERE YEAR(order_date) = $year 
        AND payment_status = 'fullypaid'
    ")->fetchColumn();
    $growth_data[] = $revenue;
}

// 5. POPULAR SERVICES
try {
    $service_types = $pdo->query("
        SELECT s.service_name, COUNT(*) as count 
        FROM order_items oi
        JOIN services s ON oi.service_id = s.service_id
        GROUP BY s.service_name
        ORDER BY count DESC 
        LIMIT 3
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $service_types = [
        ['service_name' => 'Birthday Package', 'count' => 15],
        ['service_name' => 'Corporate Event', 'count' => 8],
        ['service_name' => 'Wedding Package', 'count' => 12]
    ];
}

// 6. SERVICE RATINGS
$rating_data = [];
$services_to_rate = ['Birthday', 'Corporate', 'Wedding'];

foreach ($services_to_rate as $service) {
    try {
        $score = $pdo->query("
            SELECT IFNULL(AVG(r.rating), 4.0)
            FROM reviews r
            JOIN order_items oi ON r.order_id = oi.order_id
            JOIN services s ON oi.service_id = s.service_id
            WHERE s.service_name LIKE '%$service%'
        ")->fetchColumn();
        $rating_data[] = round($score, 1);
    } catch (PDOException $e) {
        $rating_data[] = 4.0; // Default rating if no reviews
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .metric-card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            text-align: center;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .metric-card:hover {
            transform: translateY(-5px);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .metric-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .chart-container {
            position: relative;
            height: 150px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        @media print {

            .no-print,
            .admin-header,
            .admin-sidebar {
                display: none !important;
            }

            body {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body>
    <?php 
    $reports = "active";
    include("../components/admin-header.php"); 
    ?>

    <div class="d-flex position-relative body-container">
        <?php include("../components/admin-sidebar.php"); ?>

        <div class="container-fluid py-4 mt-5" style="width: 100vw; padding-left: 300px;">
            <!-- Header Section -->
            <div class="row mb-1 pe-3">
                <div class="col-12 card mt-3 p-3 ms-2">
                    <div class="row">
                        <h1 class="h2 col-6">Sales Performance Report</h1>
                        <div class="text-end mb-3 col-6">
                            <button onclick="window.print()" class="btn btn-primary no-print">
                                <i class="fas fa-print me-2"></i>Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Row -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card metric-card bg-light-success">
                        <div class="metric-value text-success">₱<?= number_format($current_week_revenue, 2) ?></div>
                        <div class="metric-label">Current Week Revenue</div>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <?= $revenue_increase >= 0 ? '+' : '' ?><?= $revenue_increase ?>% vs last week
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card metric-card bg-light-info">
                        <div class="metric-value text-info"><?= $completion_rate ?>%</div>
                        <div class="metric-label">Completion Rate (Last Month)</div>
                        <div class="mt-3">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <?= $completed_orders ?> of <?= $total_orders_last_month ?> orders
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card metric-card bg-light-warning position-relative">
                        <div class="chart-container">
                            <canvas id="quarterGauge"></canvas>
                        </div>
                        <div class="metric-label text-center">Quarter Goal Progress</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Annual Revenue Growth</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="growthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Top Services</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($service_types as $index => $service): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-<?= $index == 0 ? 'birthday-cake' : ($index == 1 ? 'briefcase' : 'rings-wedding') ?> text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= htmlspecialchars($service['service_name']) ?></h6>
                                        <small class="text-muted"><?= $service['count'] ?> orders</small>
                                    </div>
                                    <div class="badge bg-primary bg-opacity-10 text-primary">
                                        #<?= $index + 1 ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Ratings Row -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Service Quality Ratings</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="ratingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Revenue Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(range($current_year - 7, $current_year)) ?>,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: <?= json_encode($growth_data) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Service Ratings Chart
        const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');
        new Chart(ratingsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($services_to_rate) ?>,
                datasets: [{
                    label: 'Average Rating',
                    data: <?= json_encode($rating_data) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 0.5
                        }
                    }
                }
            }
        });

        // Quarter Progress Gauge
        const quarterGaugeCtx = document.getElementById('quarterGauge').getContext('2d');
        new Chart(quarterGaugeCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [<?= $quarter_progress ?>, 100 - <?= $quarter_progress ?>],
                    backgroundColor: ['#ffc107', '#e9ecef'],
                    borderWidth: 0
                }]
            },
            options: {
                circumference: 180,
                rotation: -90,
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            },
            plugins: [{
                id: 'text',
                beforeDraw: function(chart) {
                    const width = chart.width;
                    const height = chart.height;
                    const ctx = chart.ctx;

                    ctx.restore();
                    ctx.font = "bold 2rem sans-serif";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#6c757d";

                    const text = "<?= $quarter_progress ?>%";
                    const textX = Math.round((width - ctx.measureText(text).width) / 2);
                    const textY = height / 1.7;

                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            }]
        });
    </script>
</body>

</html>