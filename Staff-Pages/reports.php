<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Reports</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/gaugeJS/dist/gauge.min.js"></script>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/report.css">
</head>

<body>
    <?php
    $reports = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="container-fluid py-4 mt-5" style="width: 100vw; padding-left: 300px;">
            <div class="row mb-1 pe-3">
                <div class="col-12 card mt-3 p-3 ms-2">
                    <h1 class="h2">Reports</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">Reports</div>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Revenue Metrics -->
                <div class="col-md-4 d-flex">
                    <div class="card metric-card flex-grow-1 mb-2" style="display: flex; justify-content: center;">
                        <div class="metric-value text-success">15%</div>
                        <div class="metric-label">Increase compared to last week</div>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-success">Revenues</span>
                        </div>
                        <!-- Spacer to match height -->
                    </div>
                </div>

                <div class="col-md-4 d-flex">
                    <div class="card metric-card flex-grow-1 mb-2" style="display: flex; justify-content: center;">
                        <div class="metric-value text-danger">4%</div>
                        <div class="metric-label">You closed 19 out of 24 events last month</div>
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
                        <div class="metric-value text-warning position-absolute top-50 start-50 translate-middle" id="gauge-value">84%</div>
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
                            <div class="btn-group export-btn" style="position: relative;">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <div class="dropdown-menu dropdown-menu-end export-options" aria-labelledby="exportDropdown">
                                    <button class="dropdown-item" type="button"><i class="fas fa-file-pdf me-2"></i>PDF</button>
                                    <button class="dropdown-item" type="button"><i class="fas fa-file-csv me-2"></i>CSV</button>
                                    <button class="dropdown-item" type="button"><i class="fas fa-file-word me-2"></i>Word</button>
                                    <button class="dropdown-item" type="button"><i class="fas fa-print me-2"></i>Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="column">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-code text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Developers</h6>
                                            <small class="text-muted">Tech industry events</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-child text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Children's Parties</h6>
                                            <small class="text-muted">Family-oriented events</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-birthday-cake text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Birthdays</h6>
                                            <small class="text-muted">Personal celebrations</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="col-md-5 row p-0">
                    <div class="col-md-12 ps-3 pe-0">
                        <div class="card metric-card" style="height: 158px;">
                            <div class="metric-value">Top 1</div>
                            <div class="metric-label">Top 1 in 3 Branches</div>
                            <div class="mt-2 trend-up">
                                <i class="fas fa-arrow-up"></i> 20% increase from last week
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 ps-3 pe-0">
                        <div class="card metric-card" style="height: 158px;">
                            <div class="metric-value">13</div>
                            <div class="metric-label">New Employees Onboarded</div>
                            <div class="mt-2 trend-up">
                                <i class="fas fa-arrow-up"></i> 15% increase from last month
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 ps-3 pe-0">
                        <div class="card metric-card" style="height: 158px;">
                            <div class="metric-value">34</div>
                            <div class="metric-label">New Clients Approached</div>
                            <div class="mt-2 trend-up">
                                <i class="fas fa-arrow-up"></i> 5% increase from last week
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-md-7">
                    <!-- Company Event Strength Chart -->
                    <div class="card" style="height: 225px;">
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Event Strength Bar Chart
        const eventStrengthCtx = document.getElementById('eventStrengthChart').getContext('2d');
        const eventStrengthChart = new Chart(eventStrengthCtx, {
            type: 'bar',
            data: {
                labels: ['Parties', 'Marketing', 'Birthdays'],
                datasets: [{
                    label: 'Rating',
                    data: [4.5, 4.5, 4.5],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
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
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'],
                datasets: [{
                    label: 'Growth Percentage',
                    data: [10, 20, 50, 100, 80, 60, 90, 120],
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
                        intersect: false
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
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });

        // Show export options on hover
        document.querySelector('.export-btn').addEventListener('mouseenter', function() {
            this.querySelector('.export-options').style.display = 'block';
        });

        document.querySelector('.export-btn').addEventListener('mouseleave', function() {
            this.querySelector('.export-options').style.display = 'none';
        });
    </script>
    <script>
        // Initialize the gauge
        const gaugeValue = 84; // Your percentage value
        const opts = {
            angle: 0, // The span of the gauge arc
            lineWidth: 0.2, // The line thickness
            radiusScale: 1, // Relative radius
            pointer: {
                length: 0.6, // Relative to gauge radius
                strokeWidth: 0.035, // The thickness
                color: '#e0e0e000' // Fill color
            },
            limitMax: false, // If false, max value increases automatically if value > maxValue
            limitMin: false, // If true, the min value of the gauge will be fixed
            colorStart: '#ffc107', // Colors
            colorStop: '#ffc107', // just experiment with them
            strokeColor: '#e0e0e0', // to see which ones work best for you
            generateGradient: true,
            highDpiSupport: true, // High resolution support
            percentColors: [
                [0.0, "#ffc107"],
                [1.0, "#ffc107"]
            ],
        };

        const target = document.getElementById('gaugeCanvas'); // your canvas element
        const gauge = new Gauge(target).setOptions(opts); // create gauge

        gauge.maxValue = 100; // set max gauge value
        gauge.setMinValue(0); // set min value
        gauge.animationSpeed = 32; // set animation speed (32 is default value)
        gauge.set(gaugeValue); // set actual value
    </script>
</body>

</html>