<?php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in or if customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

// Get revenue comparison data (current week vs last week)
$current_week_revenue = $pdo->query("
    SELECT IFNULL(SUM(amount_paid), 0) 
    FROM payment 
    WHERE WEEK(paid_date) = WEEK(CURDATE()) 
    AND payment_status = 'Paid'
")->fetchColumn();

$last_week_revenue = $pdo->query("
    SELECT IFNULL(SUM(amount_paid), 0) 
    FROM payment 
    WHERE WEEK(paid_date) = WEEK(CURDATE()) - 1 
    AND payment_status = 'Paid'
")->fetchColumn();

// Calculate revenue increase with zero division protection
$revenue_increase = 0;
if ($last_week_revenue > 0) {
    $revenue_increase = round((($current_week_revenue - $last_week_revenue) / $last_week_revenue) * 100);
} elseif ($current_week_revenue > 0) {
    $revenue_increase = 100; // 100% increase if no last week revenue but current exists
}

// Get event completion rate with zero division protection
$completed_events = $pdo->query("
    SELECT IFNULL(COUNT(*), 0) 
    FROM event_request 
    WHERE request_status = 'Approved' 
    AND MONTH(event_date) = MONTH(CURRENT_DATE) - 1
")->fetchColumn();

$total_events_last_month = $pdo->query("
    SELECT IFNULL(COUNT(*), 1) 
    FROM event_request 
    WHERE MONTH(event_date) = MONTH(CURRENT_DATE) - 1
")->fetchColumn();

$completion_rate = round(($completed_events / $total_events_last_month) * 100);
$incomplete_rate = 100 - $completion_rate;

// Quarterly goal progress (using 3 months of revenue data)
$quarter_start = date('Y-m-d', strtotime('first day of this quarter'));
$quarter_end = date('Y-m-d', strtotime('last day of this quarter'));
$quarter_days_passed = (strtotime(date('Y-m-d')) - strtotime($quarter_start)) / (60 * 60 * 24);
$quarter_days_total = (strtotime($quarter_end) - strtotime($quarter_start)) / (60 * 60 * 24);
$quarter_progress = $quarter_days_total > 0 ? round(($quarter_days_passed / $quarter_days_total) * 100) : 0;

// Growth chart data (yearly revenue)
$growth_data = [];
$current_year = date('Y');
for ($i = 0; $i < 8; $i++) {
    $year = $current_year - 7 + $i;
    $revenue = $pdo->query("
        SELECT IFNULL(SUM(amount_paid), 0) 
        FROM payment 
        WHERE YEAR(paid_date) = $year 
        AND payment_status = 'Paid'
    ")->fetchColumn();
    $growth_data[] = $revenue;
}

// Normalize growth data to percentages with zero division protection
$max_revenue = max($growth_data);
$growth_percentages = [];
if ($max_revenue > 0) {
    foreach ($growth_data as $val) {
        $growth_percentages[] = round(($val / $max_revenue) * 100);
    }
} else {
    $growth_percentages = array_fill(0, 8, 0);
}

// Marketing focus areas (based on event types)
$event_types = $pdo->query("
    SELECT event_type, COUNT(*) as count 
    FROM event_request 
    WHERE event_type IS NOT NULL
    GROUP BY event_type 
    ORDER BY count DESC 
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

// If no event types found, use defaults
if (empty($event_types)) {
    $event_types = [
        ['event_type' => 'Birthday', 'count' => 0],
        ['event_type' => 'Corporate', 'count' => 0],
        ['event_type' => 'Wedding', 'count' => 0]
    ];
}

// Event strength ratings (with fallbacks)
$strength_data = [];
$event_categories = ['Parties', 'Marketing', 'Birthdays'];

foreach ($event_categories as $category) {
    // Default rating
    $review_score = 1.0;

    // Try to get from reviews if table exists
    try {
        $score = $pdo->query("
            SELECT IFNULL(AVG(rating), 4.0) 
            FROM reviews 
            JOIN event_request ON reviews.event_id = event_request.event_id
            WHERE event_request.event_type LIKE '%$category%'
        ")->fetchColumn();

        $review_score = round($score, 1);
    } catch (PDOException $e) {
        // Fallback to event count based rating
        $count = $pdo->query("
            SELECT IFNULL(COUNT(*), 0) 
            FROM event_request 
            WHERE event_type LIKE '%$category%'
        ")->fetchColumn();

        if ($count > 0) {
            $review_score = min(5.0, 3.5 + ($count * 0.1));
            $review_score = round($review_score, 1);
        }
    }

    $strength_data[] = $review_score;
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
    <script src="https://cdn.jsdelivr.net/npm/gaugeJS/dist/gauge.min.js"></script>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/report.css">
</head>
<style>
    @media print {

        /* Hide header and sidebar */
        .admin-header,
        .admin-sidebar {
            display: none !important;
        }

        /* Adjust the main content to take full width */
        .body-container {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }

        /* Remove padding and margins for printing */
        .container-fluid {
            width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            margin-left: 0 !important;
        }

        /* Make sure cards don't break across pages */
        .card {
            page-break-inside: avoid;
        }

        /* Add some padding to the top of the printed page */
        body {
            padding-top: 20px !important;
        }

        /* Hide any buttons or interactive elements */
        .dropdown,
        .btn {
            display: none !important;
        }
    }
</style>

<body>
    <?php
    $reports = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php include("../components/admin-sidebar.php"); ?>
        <div class="container-fluid py-4 mt-5" style="width: 100vw; padding-left: 300px;">
            <div class="row mb-1 pe-3">
                <div class="col-12 card mt-3 p-3 ms-2">
                <div class="row">
                    <h1 class="h2 col-6">Reports</h1>
                    <div class="text-end mb-3 col-6">
                        <button onclick="window.print()" class="btn btn-purple">
                            <i class="fas fa-print me-2"></i>Print Report
                        </button>
                    </div>
                </div>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">Reports</div>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Revenue Metrics -->
                <div class="col-md-4 d-flex">
                    <div class="card metric-card flex-grow-1 mb-2" style="display: flex; justify-content: center;">
                        <div class="metric-value text-success"><?= $revenue_increase ?>%</div>
                        <div class="metric-label">Increase compared to last week</div>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-success">Revenues</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="card metric-card flex-grow-1 mb-2" style="display: flex; justify-content: center;">
                        <div class="metric-value text-danger"><?= $incomplete_rate ?>%</div>
                        <div class="metric-label">You closed <?= $completed_events ?> out of <?= $total_events_last_month ?> events last month</div>
                        <div class="mt-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger">Loss revenues</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="card metric-card position-relative flex-grow-1 mb-2" style="display: flex; justify-content: center;">
                        <div class="gauge-container">
                            <canvas id="gaugeCanvas" class="gauge-body" style="width: 200px;"></canvas>
                        </div>
                        <div class="metric-value text-warning position-absolute top-50 start-50 translate-middle" id="gauge-value"><?= $quarter_progress ?>%</div>
                        <div class="metric-label">Quarter goal progress</div>
                        <div class="mt-3">
                            <span class="badge bg-warning bg-opacity-10 text-warning">Quarter goal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-7 column">
                    <div class="col-12">
                        <div class="card pe-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Growth</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="growthDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Yearly
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="growthDropdown">
                                        <li><a class="dropdown-item" href="#">Monthly</a></li>
                                        <li><a class="dropdown-item" href="#">Quarterly</a></li>
                                        <li><a class="dropdown-item" href="#">Yearly</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-0" style="height: 260px; width: 100%;">
                                <canvas id="growthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketing Focus Areas -->
                <div class="col-md-5">
                    <div class="card" style="height: 308px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Marketing</span>
                        </div>
                        <div class="card-body">
                            <div class="column">
                                <?php foreach ($event_types as $index => $type): ?>
                                    <?php
                                    $colors = ['primary', 'success', 'info'];
                                    $icons = ['code', 'child', 'birthday-cake'];
                                    ?>
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-<?= $colors[$index] ?> bg-opacity-10 p-2 rounded me-3">
                                                <i class="fas fa-<?= $icons[$index] ?> text-<?= $colors[$index] ?>"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($type['event_type']) ?></h6>
                                                <small class="text-muted"><?= $type['count'] ?> events</small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Event Strength Chart -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Company Event Strength</span>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="eventStrengthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(range(date('Y') - 7, date('Y'))) ?>,
                datasets: [{
                    label: 'Growth Percentage',
                    data: <?= json_encode($growth_percentages) ?>,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% growth';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // Event Strength Chart
        const eventStrengthCtx = document.getElementById('eventStrengthChart').getContext('2d');
        const eventStrengthChart = new Chart(eventStrengthCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($event_categories) ?>,
                datasets: [{
                    label: 'Rating',
                    data: <?= json_encode($strength_data) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 0.5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gauge Chart
        const gaugeValue = <?= $quarter_progress ?>;
        const opts = {
            angle: 0,
            lineWidth: 0.2,
            radiusScale: 1,
            pointer: {
                length: 0.6,
                strokeWidth: 0.035,
                color: '#e0e0e000'
            },
            limitMax: false,
            limitMin: false,
            colorStart: '#ffc107',
            colorStop: '#ffc107',
            strokeColor: '#e0e0e0',
            generateGradient: true,
            highDpiSupport: true,
            percentColors: [
                [0.0, "#ffc107"],
                [1.0, "#ffc107"]
            ],
        };

        const target = document.getElementById('gaugeCanvas');
        const gauge = new Gauge(target).setOptions(opts);
        gauge.maxValue = 100;
        gauge.setMinValue(0);
        gauge.animationSpeed = 32;
        gauge.set(gaugeValue);

        // Export dropdown hover effect
        document.querySelector('.export-btn').addEventListener('mouseenter', function() {
            this.querySelector('.export-options').style.display = 'block';
        });

        document.querySelector('.export-btn').addEventListener('mouseleave', function() {
            this.querySelector('.export-options').style.display = 'none';
        });
    </script>
</body>

</html>